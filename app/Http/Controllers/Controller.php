<?php 
// File utama controller dasar aplikasi

// Namespace untuk controller
namespace App\Http\Controllers; 

/**
 * Controller dasar untuk aplikasi Laravel.
 *
 * Kelas ini berfungsi sebagai induk dari semua controller lain.
 * Tempat menaruh logika umum yang sering digunakan di banyak controller.
 *
 * Tips:
 * - Untuk membuat controller baru, gunakan perintah artisan.
 * - Middleware atau fungsi yang sering dipakai bisa diletakkan di sini.
 * - Kelas ini abstrak, hanya sebagai dasar, tidak untuk di-instantiate langsung.
 */
abstract class Controller
{
    // Tambahkan method atau property yang ingin digunakan di semua controller di sini
    // Contoh: helper untuk response, logging, atau middleware global
    // Misal:
    // protected function respondSuccess($data) { return response()->json($data); }
}
