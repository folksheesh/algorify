<?php

namespace App\Imports;

use App\Models\Soal;
use App\Models\PilihanJawaban;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class SoalImport implements ToCollection, WithHeadingRow
{
    protected $ujianId;
    protected $kursusId;

    public function __construct($ujianId, $kursusId)
    {
        $this->ujianId = $ujianId;
        $this->kursusId = $kursusId;
    }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        
        try {
            foreach ($rows as $row) {
                // Convert row to array and get keys
                $rowArray = $row->toArray();
                
                // Validate required fields - using the actual slugified keys from Excel
                if (empty($rowArray['pertanyaan']) || 
                    empty($rowArray['pilihan_a']) || 
                    empty($rowArray['pilihan_b']) || 
                    empty($rowArray['pilihan_c']) || 
                    empty($rowArray['pilihan_d']) || 
                    empty($rowArray['kunci_jawaban_abcd'])) {
                    continue; // Skip invalid rows
                }

                // Create soal
                $soal = Soal::create([
                    'ujian_id' => $this->ujianId,
                    'kursus_id' => $this->kursusId,
                    'pertanyaan' => $rowArray['pertanyaan'],
                    'kunci_jawaban' => strtoupper(trim($rowArray['kunci_jawaban_abcd'])),
                ]);

                // Create pilihan jawaban
                $pilihan = [
                    'A' => $rowArray['pilihan_a'],
                    'B' => $rowArray['pilihan_b'],
                    'C' => $rowArray['pilihan_c'],
                    'D' => $rowArray['pilihan_d'],
                ];

                $kunciJawaban = strtoupper(trim($rowArray['kunci_jawaban_abcd']));

                foreach ($pilihan as $key => $text) {
                    PilihanJawaban::create([
                        'soal_id' => $soal->id,
                        'pilihan' => $text,
                        'is_correct' => $key === $kunciJawaban,
                    ]);
                }
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function headingRow(): int
    {
        return 1;
    }
}
