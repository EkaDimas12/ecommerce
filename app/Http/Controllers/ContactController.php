<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
  public function store(Request $request) {
    $request->validate([
      'name'=>'required|string|max:120',
      'email'=>'nullable|email|max:120',
      'phone'=>'nullable|string|max:30',
      'message'=>'required|string|max:1000',
    ]);

    ContactMessage::create($request->only('name','email','phone','message'));

    return back()->with('toast', ['message'=>'Pesan berhasil dikirim. Kami akan membalas secepatnya.', 'type'=>'success']);
  }
}

