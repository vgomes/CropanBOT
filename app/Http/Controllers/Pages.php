<?php

namespace Cropan\Http\Controllers;

use Cropan\Http\Requests;
use Cropan\Picture;

class Pages extends Controller
{
    public function index() {
        return 'hola';
    }

    public function history() {
        $pictures = Picture::whereNotNull('sent_at')->orderBy('created_at', 'desc')->paginate(15);
        
        return view('pages.history')->with('pictures', $pictures);
    }
}
