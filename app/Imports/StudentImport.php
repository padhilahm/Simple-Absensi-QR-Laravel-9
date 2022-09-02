<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // skip heading
        if ($row[0] == 'Nama' && $row[1] == 'NIS' && $row[2] == 'Kode Kelas') {
            return null;
        }

        return new Student([
            'name'              => $row[0],
            'student_id_number' => $row[1],
            'student_class_id'  => $row[2],
        ]);
    }
}
