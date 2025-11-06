<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class PelatihanController extends Controller
{
    /**
     * Display a listing of the user's enrollments (Pelatihan Saya).
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $enrollments = collect();
        $dbError = false;

        if ($user) {
            try {
                $enrollments = Enrollment::with('kursus')
                    ->where('user_id', $user->id)
                    ->orderBy('tanggal_daftar', 'desc')
                    ->get();
            } catch (\PDOException $e) {
                // Could not find driver or other low-level PDO error
                Log::error('PelatihanController@index PDOException: '.$e->getMessage());
                $dbError = true;
                $enrollments = collect();
            } catch (QueryException $e) {
                // Query level error (also covers driver missing in some cases)
                Log::error('PelatihanController@index QueryException: '.$e->getMessage());
                $dbError = true;
                $enrollments = collect();
            }
        }

        return view('pelatihan.index', compact('enrollments', 'dbError'));
    }

    /**
     * Show a specific enrollment / pelatihan detail (optional).
     */
    public function show($id)
    {
        $dbError = false;
        $enrollment = null;
        try {
            $enrollment = Enrollment::with('kursus')->findOrFail($id);
        } catch (\PDOException $e) {
            Log::error('PelatihanController@show PDOException: '.$e->getMessage());
            $dbError = true;
        } catch (QueryException $e) {
            Log::error('PelatihanController@show QueryException: '.$e->getMessage());
            $dbError = true;
        }

        // If we couldn't load the enrollment due to DB error, show a friendly page instead of aborting
        if ($dbError) {
            return view('pelatihan.show', compact('enrollment', 'dbError'));
        }

        // Basic authorization: ensure the logged in user owns this enrollment
        if (auth()->check() && auth()->id() !== $enrollment->user_id) {
            abort(403);
        }

        return view('pelatihan.show', compact('enrollment'));
    }
}
