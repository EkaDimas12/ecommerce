<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:60'],
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'message' => ['required', 'string', 'max:600'],
        ]);

        // Simpan testimonial
        // Jika kamu ingin moderasi: set is_active = false
        // Kalau mau langsung tampil di home: set true
        Testimonial::create([
            'name'      => $data['name'],
            'rating'    => $data['rating'],
            'message'   => $data['message'],
            'is_active' => true,
        ]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Terima kasih! Testimoni kamu berhasil dikirim.',
        ]);
    }
}
