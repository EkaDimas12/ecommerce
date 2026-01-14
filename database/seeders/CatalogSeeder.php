<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;

class CatalogSeeder extends Seeder
{
  public function run(): void
  {
    $cats = collect([
      ['name'=>'Aksesoris','slug'=>'aksesoris'],
      ['name'=>'Fashion','slug'=>'fashion'],
      ['name'=>'Dekorasi','slug'=>'dekorasi'],
      ['name'=>'Souvenir','slug'=>'souvenir'],
      ['name'=>'Custom Order','slug'=>'custom-order'],
    ])->map(fn($c) => Category::create($c));

    $bySlug = fn($slug) => $cats->firstWhere('slug', $slug);

    Product::create([
      'category_id' => $bySlug('aksesoris')->id,
      'name' => 'Gantungan Kunci Resin Floral',
      'slug' => Str::slug('Gantungan Kunci Resin Floral'),
      'price' => 25000,
      'description' => 'Resin bening dengan bunga kering, cocok souvenir. Finishing glossy, ringan, dan tahan lama.',
      'is_best_seller' => true,
      'main_image' => 'images/products/resin-keychain.jpg',
    ]);

    Product::create([
      'category_id' => $bySlug('fashion')->id,
      'name' => 'Totebag Lukis Custom',
      'slug' => Str::slug('Totebag Lukis Custom'),
      'price' => 85000,
      'description' => 'Totebag kanvas, bisa request nama/ilustrasi. Cocok untuk hadiah dan kebutuhan harian.',
      'is_best_seller' => true,
      'main_image' => 'images/products/totebag-custom.jpg',
    ]);

    Product::create([
      'category_id' => $bySlug('dekorasi')->id,
      'name' => 'Hiasan Dinding Makrame',
      'slug' => Str::slug('Hiasan Dinding Makrame'),
      'price' => 120000,
      'description' => 'Makrame handmade, ukuran 30x50 cm. Gaya boho-minimal, cocok ruang tamu/kamar.',
      'is_best_seller' => false,
      'main_image' => 'images/products/makrame-wall.jpg',
    ]);

    Product::create([
      'category_id' => $bySlug('souvenir')->id,
      'name' => 'Paket Souvenir Pernikahan (10 pcs)',
      'slug' => Str::slug('Paket Souvenir Pernikahan (10 pcs)'),
      'price' => 200000,
      'description' => 'Paket hemat 10 pcs, bisa custom warna & kartu ucapan. Cocok wedding/engagement/acara.',
      'is_best_seller' => true,
      'main_image' => 'images/products/wedding-souvenir.jpg',
    ]);

    foreach ([
      ['name'=>'Nabila','rating'=>5,'message'=>'Produk rapi, bunganya cantik banget! Packing aman.','is_active'=>true],
      ['name'=>'Raka','rating'=>5,'message'=>'Totebagnya sesuai request, hasil lukisannya halus.','is_active'=>true],
      ['name'=>'Sinta','rating'=>5,'message'=>'Makrame bikin ruang tamu jadi estetik.','is_active'=>true],
      ['name'=>'Dimas','rating'=>4,'message'=>'Souvenir wedding hemat dan cepat diproses.','is_active'=>true],
    ] as $t) {
      Testimonial::create($t);
    }
  }
}
