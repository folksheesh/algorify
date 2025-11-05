<?php // Request khusus untuk update profil user

namespace App\Http\Requests; // Namespace request

use App\Models\User; // Import model User
use Illuminate\Foundation\Http\FormRequest; // Import FormRequest
use Illuminate\Validation\Rule; // Import Rule untuk validasi unik

// Kelas ini menangani validasi data update profil user
class ProfileUpdateRequest extends FormRequest
{
    /**
     * Mendefinisikan aturan validasi untuk update profil user.
     * Nama wajib diisi, email harus unik dan valid, field lain opsional.
     * Validasi juga untuk upload foto dan ganti password.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'profesi' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'pendidikan' => ['nullable', 'string', 'max:255'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpeg,png,gif', 'max:2048'],
            'password_lama' => ['nullable', 'string'],
            'password_baru' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }
}
