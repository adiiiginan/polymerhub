<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'belakang' => 'required',
            'negara' => 'required',
            'email' => 'required|email',
            'notlp' => 'required',
            'perusahaan' => 'required',
            'catatan' => 'required',
            'privacy' => 'accepted',
        ]);

        Contact::create([
            'nama' => $request->nama,
            'belakang' => $request->belakang,
            'negara' => $request->negara,
            'email' => $request->email,
            'notlp' => $request->notlp,
            'perusahaan' => $request->perusahaan,
            'catatan' => $request->catatan,
        ]);

        return back()->with('success', 'Thank you for your message. We will get back to you shortly.');
    }
}
