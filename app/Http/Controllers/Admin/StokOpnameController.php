<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StokOpnameController extends Controller
{
    public function index()
    {
        return "This is the stock opname index.";
    }

    public function create()
    {
        return "This is the page to create a new stock opname.";
    }

    public function selesai()
    {
        return "This is the stock opname selesai page.";
    }
}
