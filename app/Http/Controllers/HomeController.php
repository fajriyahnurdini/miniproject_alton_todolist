<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Mengarahkan langsung ke halaman utama To-Do List
        return redirect()->route('todos.index');
    }
}
