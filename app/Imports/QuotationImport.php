<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class QuotationImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        // dd("test");
        // dd($rows[0]);
        foreach ($rows[0][0] as $row) {
            // Proses setiap baris (row) di sini
            // dd($row);
            $data = [
                'kolom1' => $row['nama_kolom1'],
                'kolom2' => $row['nama_kolom2'],
                // Sesuaikan dengan nama kolom pada file Excel dan model Anda.
            ];

            // Lakukan sesuatu dengan data, misalnya simpan ke database
            // Contoh: YourModel::create($data);
        }
    }
}
