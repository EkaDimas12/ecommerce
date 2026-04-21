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

        // Simpan testimonial dengan is_active = false (perlu moderasi admin)
        Testimonial::create([
            'name'      => $data['name'],
            'rating'    => $data['rating'],
            'message'   => $data['message'],
            'is_active' => false,
        ]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Terima kasih! Testimoni kamu berhasil dikirim.',
        ]);
    }
}
