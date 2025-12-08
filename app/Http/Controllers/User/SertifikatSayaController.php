<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Sertifikat;
use App\Models\Kursus;
use Illuminate\Http\Request;

class SertifikatSayaController extends Controller
{
    public function index()
    {
        return view('user.sertifikat.index');
    }
}
