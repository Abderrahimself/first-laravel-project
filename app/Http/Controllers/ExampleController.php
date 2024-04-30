<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function homepage()
    {
        return view('homepage');
    }

    public function about()
    {
        return view('single-post');
    }
}
