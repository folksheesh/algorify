<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SertifikatController extends Controller
{
    public function index()
    {
        return view('admin.sertifikat.index');
    }
}
