<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure roles exist
        $pengajarRole = Role::firstOrCreate(['name' => 'pengajar', 'guard_name' => 'web']);
        $pesertaRole = Role::firstOrCreate(['name' => 'peserta', 'guard_name' => 'web']);

        // ========================================
        // PENGAJAR (21 users)
        // ========================================

        $pengajarData = [
            [
                'name' => 'Dr. Budi Santoso',
                'email' => 'budi@algorify.com',
                'phone' => '081234567891',
                'profesi' => 'Data Scientist',
                'address' => 'Bandung',
                'pendidikan' => 'S3 Computer Science',
                'tanggal_lahir' => '1980-05-15',
                'jenis_kelamin' => 'L',
            ],
            [
                'name' => 'Sarah Wijaya, M.Kom',
                'email' => 'sarah@algorify.com',
                'phone' => '081234567892',
                'profesi' => 'Cyber Security Expert',
                'address' => 'Surabaya',
                'pendidikan' => 'S2 Cyber Security',
                'tanggal_lahir' => '1985-08-20',
                'jenis_kelamin' => 'P',
            ],
            [
                'name' => 'Andi Prasetyo',
                'email' => 'andi@algorify.com',
                'phone' => '081234567893',
                'profesi' => 'UI/UX Designer',
                'address' => 'Yogyakarta',
                'pendidikan' => 'S1 Desain Komunikasi Visual',
                'tanggal_lahir' => '1990-03-10',
                'jenis_kelamin' => 'L',
            ],
            [
                'name' => 'Dewi Kusuma',
                'email' => 'dewi@algorify.com',
                'phone' => '081234567894',
                'profesi' => 'Full Stack Developer',
                'address' => 'Jakarta',
                'pendidikan' => 'S1 Teknik Informatika',
                'tanggal_lahir' => '1988-11-25',
                'jenis_kelamin' => 'P',
            ],
            [
                'name' => 'Rizki Pratama, M.T.',
                'email' => 'rizki.pratama@algorify.com',
                'phone' => '081234567801',
                'profesi' => 'Machine Learning Engineer',
                'address' => 'Bekasi',
                'pendidikan' => 'S2 Teknik Informatika',
                'tanggal_lahir' => '1987-07-12',
                'jenis_kelamin' => 'L',
            ],
            [
                'name' => 'Nurul Hidayah, S.Kom',
                'email' => 'nurul.hidayah@algorify.com',
                'phone' => '081234567802',
                'profesi' => 'Backend Developer',
                'address' => 'Depok',
                'pendidikan' => 'S1 Ilmu Komputer',
                'tanggal_lahir' => '1992-02-28',
                'jenis_kelamin' => 'P',
            ],
            [
                'name' => 'Agus Setiawan',
                'email' => 'agus.setiawan@algorify.com',
                'phone' => '081234567803',
                'profesi' => 'DevOps Engineer',
                'address' => 'Tangerang',
                'pendidikan' => 'S1 Sistem Informasi',
                'tanggal_lahir' => '1989-09-05',
                'jenis_kelamin' => 'L',
            ],
            [
                'name' => 'Maya Sari, M.Cs',
                'email' => 'maya.sari@algorify.com',
                'phone' => '081234567804',
                'profesi' => 'Cloud Architect',
                'address' => 'Bogor',
                'pendidikan' => 'S2 Computer Science',
                'tanggal_lahir' => '1986-04-18',
                'jenis_kelamin' => 'P',
            ],
            [
                'name' => 'Hendra Gunawan',
                'email' => 'hendra.gunawan@algorify.com',
                'phone' => '081234567805',
                'profesi' => 'Mobile Developer',
                'address' => 'Semarang',
                'pendidikan' => 'S1 Teknik Elektro',
                'tanggal_lahir' => '1991-12-01',
                'jenis_kelamin' => 'L',
            ],
            [
                'name' => 'Putri Anggraini, M.M.',
                'email' => 'putri.anggraini@algorify.com',
                'phone' => '081234567806',
                'profesi' => 'Product Manager',
                'address' => 'Surabaya',
                'pendidikan' => 'S2 Manajemen',
                'tanggal_lahir' => '1988-06-22',
                'jenis_kelamin' => 'P',
            ],
            [
                'name' => 'Fajar Nugroho',
                'email' => 'fajar.nugroho@algorify.com',
                'phone' => '081234567807',
                'profesi' => 'Blockchain Developer',
                'address' => 'Jakarta',
                'pendidikan' => 'S1 Teknik Informatika',
                'tanggal_lahir' => '1993-01-30',
                'jenis_kelamin' => 'L',
            ],
            [
                'name' => 'Sinta Dewanti, S.T.',
                'email' => 'sinta.dewanti@algorify.com',
                'phone' => '081234567808',
                'profesi' => 'IoT Specialist',
                'address' => 'Bandung',
                'pendidikan' => 'S1 Teknik Elektro',
                'tanggal_lahir' => '1990-08-15',
                'jenis_kelamin' => 'P',
            ],
            [
                'name' => 'Ahmad Fauzi, M.Kom',
                'email' => 'ahmad.fauzi@algorify.com',
                'phone' => '081234567809',
                'profesi' => 'AI Researcher',
                'address' => 'Yogyakarta',
                'pendidikan' => 'S2 Kecerdasan Buatan',
                'tanggal_lahir' => '1985-11-08',
                'jenis_kelamin' => 'L',
            ],
            [
                'name' => 'Rina Maharani',
                'email' => 'rina.maharani@algorify.com',
                'phone' => '081234567810',
                'profesi' => 'Game Developer',
                'address' => 'Malang',
                'pendidikan' => 'S1 Teknik Informatika',
                'tanggal_lahir' => '1994-03-25',
                'jenis_kelamin' => 'P',
            ],
            [
                'name' => 'Benny Kurniawan',
                'email' => 'benny.kurniawan@algorify.com',
                'phone' => '081234567811',
                'profesi' => 'Network Engineer',
                'address' => 'Medan',
                'pendidikan' => 'S1 Teknik Komputer',
                'tanggal_lahir' => '1989-05-12',
                'jenis_kelamin' => 'L',
            ],
            [
                'name' => 'Dina Permata, S.Ds.',
                'email' => 'dina.permata@algorify.com',
                'phone' => '081234567812',
                'profesi' => 'Motion Graphic Designer',
                'address' => 'Bali',
                'pendidikan' => 'S1 Desain Grafis',
                'tanggal_lahir' => '1992-09-18',
                'jenis_kelamin' => 'P',
            ],
            [
                'name' => 'Eko Prasetya',
                'email' => 'eko.prasetya@algorify.com',
                'phone' => '081234567813',
                'profesi' => 'QA Engineer',
                'address' => 'Solo',
                'pendidikan' => 'S1 Sistem Informasi',
                'tanggal_lahir' => '1991-07-05',
                'jenis_kelamin' => 'L',
            ],
            [
                'name' => 'Gita Nirmala, M.T.',
                'email' => 'gita.nirmala@algorify.com',
                'phone' => '081234567814',
                'profesi' => 'Embedded Systems Engineer',
                'address' => 'Semarang',
                'pendidikan' => 'S2 Teknik Elektro',
                'tanggal_lahir' => '1987-12-20',
                'jenis_kelamin' => 'P',
            ],
            [
                'name' => 'Irwan Saputra',
                'email' => 'irwan.saputra@algorify.com',
                'phone' => '081234567815',
                'profesi' => 'Database Administrator',
                'address' => 'Makassar',
                'pendidikan' => 'S1 Ilmu Komputer',
                'tanggal_lahir' => '1990-02-14',
                'jenis_kelamin' => 'L',
            ],
            [
                'name' => 'Julia Hartono',
                'email' => 'julia.hartono@algorify.com',
                'phone' => '081234567816',
                'profesi' => 'Business Intelligence Analyst',
                'address' => 'Surabaya',
                'pendidikan' => 'S1 Statistika',
                'tanggal_lahir' => '1993-10-03',
                'jenis_kelamin' => 'P',
            ],
            [
                'name' => 'Pengajar Demo',
                'email' => 'pengajar@example.com',
                'phone' => '081200000001',
                'profesi' => 'Senior Instructor',
                'address' => 'Jakarta',
                'pendidikan' => 'S2 Pendidikan',
                'tanggal_lahir' => '1985-06-15',
                'jenis_kelamin' => 'L',
                'password' => 'pengajar123',
            ],
        ];

        foreach ($pengajarData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'id' => User::generateId('pengajar'),
                    'name' => $data['name'],
                    'password' => Hash::make($data['password'] ?? 'password'),
                    'phone' => $data['phone'],
                    'profesi' => $data['profesi'],
                    'address' => $data['address'],
                    'pendidikan' => $data['pendidikan'],
                    'tanggal_lahir' => $data['tanggal_lahir'],
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'email_verified_at' => now(),
                    'status' => 'active',
                ]
            );
            if (!$user->hasRole('pengajar')) {
                $user->assignRole('pengajar');
            }
        }

        // ========================================
        // PESERTA (20 users)
        // ========================================

        $pesertaData = [
            [
                'name' => 'Muhammad Zein',
                'email' => 'zein@student.com',
                'phone' => '081234567895',
                'profesi' => 'Mahasiswa',
                'address' => 'Semarang',
                'pendidikan' => 'S1 Teknik Informatika',
                'tanggal_lahir' => '2000-01-15',
                'jenis_kelamin' => 'L',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@student.com',
                'phone' => '081234567896',
                'profesi' => 'Fresh Graduate',
                'address' => 'Malang',
                'pendidikan' => 'S1 Sistem Informasi',
                'tanggal_lahir' => '1999-05-22',
                'jenis_kelamin' => 'P',
            ],
            [
                'name' => 'Rudi Hermawan',
                'email' => 'rudi@student.com',
                'phone' => '081234567897',
                'profesi' => 'IT Support',
                'address' => 'Medan',
                'pendidikan' => 'D3 Teknik Komputer',
                'tanggal_lahir' => '1998-08-10',
                'jenis_kelamin' => 'L',
            ],
            [
                'name' => 'Linda Septiani',
                'email' => 'linda@student.com',
                'phone' => '081234567898',
                'profesi' => 'Digital Marketer',
                'address' => 'Denpasar',
                'pendidikan' => 'S1 Marketing',
                'tanggal_lahir' => '1997-09-30',
                'jenis_kelamin' => 'P',
            ],
            [
                'name' => 'Arief Rahman',
                'email' => 'arief@student.com',
                'phone' => '081234567899',
                'profesi' => 'Web Developer',
                'address' => 'Jakarta',
                'pendidikan' => 'SMK Rekayasa Perangkat Lunak',
                'tanggal_lahir' => '2001-03-05',
                'jenis_kelamin' => 'L',
            ],
            [
                'name' => 'Ratna Dewi',
                'email' => 'ratna.dewi@student.com',
                'phone' => '081234567900',
                'profesi' => 'Mahasiswa',
                'address' => 'Bandung',
                'pendidikan' => 'S1 Teknik Informatika',
                'tanggal_lahir' => '2000-07-18',
                'jenis_kelamin' => 'P',
            ],
            [
                'name' => 'Bagus Firmansyah',
                'email' => 'bagus.firmansyah@student.com',
                'phone' => '081234567901',
                'profesi' => 'Junior Developer',
                'address' => 'Surabaya',
                'pendidikan' => 'S1 Ilmu Komputer',
                'tanggal_lahir' => '1999-11-25',
                'jenis_kelamin' => 'L',
            ],
            [
                'name' => 'Anisa Putri',
                'email' => 'anisa.putri@student.com',
                'phone' => '081234567902',
                'profesi' => 'UI Designer',
                'address' => 'Yogyakarta',
                'pendidikan' => 'S1 Desain Grafis',
                'tanggal_lahir' => '1998-04-12',
                'jenis_kelamin' => 'P',
            ],
            [
                'name' => 'Dimas Prasetyo',
                'email' => 'dimas.prasetyo@student.com',
                'phone' => '081234567903',
                'profesi' => 'Data Analyst',
                'address' => 'Bekasi',
                'pendidikan' => 'S1 Statistika',
                'tanggal_lahir' => '1997-06-08',
                'jenis_kelamin' => 'L',
            ],
            [
                'name' => 'Fitri Handayani',
                'email' => 'fitri.handayani@student.com',
                'phone' => '081234567904',
                'profesi' => 'Content Writer',
                'address' => 'Depok',
                'pendidikan' => 'S1 Komunikasi',
                'tanggal_lahir' => '2000-02-14',
                'jenis_kelamin' => 'P',
            ],
            [
                'name' => 'Gilang Ramadhan',
                'email' => 'gilang.ramadhan@student.com',
                'phone' => '081234567905',
                'profesi' => 'Freelancer',
                'address' => 'Tangerang',
                'pendidikan' => 'S1 Teknik Elektro',
                'tanggal_lahir' => '1996-10-20',
                'jenis_kelamin' => 'L',
            ],
            [
                'name' => 'Hasna Fadilah',
                'email' => 'hasna.fadilah@student.com',
                'phone' => '081234567906',
                'profesi' => 'Mahasiswa',
                'address' => 'Bogor',
                'pendidikan' => 'S1 Sistem Informasi',
                'tanggal_lahir' => '2001-08-05',
                'jenis_kelamin' => 'P',
            ],
            [
                'name' => 'Irfan Maulana',
                'email' => 'irfan.maulana@student.com',
                'phone' => '081234567907',
                'profesi' => 'Backend Developer',
                'address' => 'Cirebon',
                'pendidikan' => 'D4 Teknik Informatika',
                'tanggal_lahir' => '1998-12-30',
                'jenis_kelamin' => 'L',
            ],
            [
                'name' => 'Jasmine Aulia',
                'email' => 'jasmine.aulia@student.com',
                'phone' => '081234567908',
                'profesi' => 'Product Manager',
                'address' => 'Makassar',
                'pendidikan' => 'S1 Manajemen',
                'tanggal_lahir' => '1997-03-17',
                'jenis_kelamin' => 'P',
            ],
            [
                'name' => 'Kevin Wijaya',
                'email' => 'kevin.wijaya@student.com',
                'phone' => '081234567909',
                'profesi' => 'QA Engineer',
                'address' => 'Palembang',
                'pendidikan' => 'S1 Teknik Informatika',
                'tanggal_lahir' => '1999-07-22',
                'jenis_kelamin' => 'L',
            ],
            [
                'name' => 'Laras Ayu',
                'email' => 'laras.ayu@student.com',
                'phone' => '081234567910',
                'profesi' => 'Graphic Designer',
                'address' => 'Solo',
                'pendidikan' => 'D3 Desain Grafis',
                'tanggal_lahir' => '2000-05-11',
                'jenis_kelamin' => 'P',
            ],
            [
                'name' => 'Muhammad Faisal',
                'email' => 'faisal@student.com',
                'phone' => '081234567911',
                'profesi' => 'Frontend Developer',
                'address' => 'Pekanbaru',
                'pendidikan' => 'S1 Teknik Informatika',
                'tanggal_lahir' => '1998-09-28',
                'jenis_kelamin' => 'L',
            ],
            [
                'name' => 'Nabila Putri',
                'email' => 'nabila.putri@student.com',
                'phone' => '081234567912',
                'profesi' => 'Business Analyst',
                'address' => 'Banjarmasin',
                'pendidikan' => 'S1 Sistem Informasi',
                'tanggal_lahir' => '1999-01-09',
                'jenis_kelamin' => 'P',
            ],
            [
                'name' => 'Oscar Pratama',
                'email' => 'oscar.pratama@student.com',
                'phone' => '081234567913',
                'profesi' => 'Network Engineer',
                'address' => 'Balikpapan',
                'pendidikan' => 'S1 Teknik Komputer',
                'tanggal_lahir' => '1997-11-15',
                'jenis_kelamin' => 'L',
            ],
            [
                'name' => 'Peserta Demo',
                'email' => 'peserta@example.com',
                'phone' => '081200000002',
                'profesi' => 'Mahasiswa',
                'address' => 'Bandung',
                'pendidikan' => 'S1 Informatika',
                'tanggal_lahir' => '2000-06-20',
                'jenis_kelamin' => 'L',
                'password' => 'peserta123',
            ],
        ];

        foreach ($pesertaData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'id' => User::generateId('peserta'),
                    'name' => $data['name'],
                    'password' => Hash::make($data['password'] ?? 'password'),
                    'phone' => $data['phone'],
                    'profesi' => $data['profesi'],
                    'address' => $data['address'],
                    'pendidikan' => $data['pendidikan'],
                    'tanggal_lahir' => $data['tanggal_lahir'],
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'email_verified_at' => now(),
                    'status' => 'active',
                ]
            );
            if (!$user->hasRole('peserta')) {
                $user->assignRole('peserta');
            }
        }

        echo "✓ UserSeeder berhasil! Total: 21 Pengajar + 20 Peserta\n";
    }
}
