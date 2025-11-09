<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.peserta.index');
    }

    public function getData(Request $request)
    {
        $query = User::role('peserta')
            ->withCount('enrollments as kursus_count')
            ->with('enrollments');

        $peserta = $query->orderBy('created_at', 'desc')->get();

        return response()->json($peserta);
    }
}
