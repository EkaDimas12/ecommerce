<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Controller untuk mengelola data kupon diskon di halaman panel Admin.
 * Mendukung fungsionalitas CRUD (Create, Read, Update, Delete).
 */
class CouponAdminController extends Controller
{
    /**
     * Menampilkan daftar semua kupon dengan fitur pagination.
     */
    public function index()
    {
        $coupons = Coupon::latest()->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Menampilkan form untuk membuat kupon baru.
     */
    public function create()
    {
        return view('admin.coupons.form');
    }

    /**
     * Menyimpan data kupon baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input: kode harus unik, tipe harus fixed/percent, dll.
        $data = $request->validate([
            'code' => 'required|string|unique:coupons,code|max:50',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Kupon berhasil dibuat');
    }

    /**
     * Menampilkan form untuk mengedit kupon yang sudah ada.
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.form', compact('coupon'));
    }

    /**
     * Memperbarui data kupon yang sudah ada di database.
     */
    public function update(Request $request, Coupon $coupon)
    {
        // Validasi input, abaikan aturan unik untuk kode kupon yang sedang diedit ini
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('coupons')->ignore($coupon->id)],
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        // Cek apakah checkbox is_active dicentang
        $data['is_active'] = $request->has('is_active');

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Kupon berhasil diupdate');
    }

    /**
     * Menghapus data kupon dari database.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Kupon berhasil dihapus');
    }
}
