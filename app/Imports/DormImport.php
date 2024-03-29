<?php

namespace App\Imports;

use App\Models\Dorm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DormImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function __construct($organ)
    {
        $this->organ = $organ;
        //trymethod();
        //echo ($this->organ);
    }


    public function model(array $row)
    {
        // if (!isset($row['nama_asrama']) || !isset($row['kapasiti']) || !isset($row['bilangan_pelajar_dalam'])) {
        //     throw ValidationException::withMessages(["error" => "Invalid headers or missing column"]);
        // }

        // if (!isset($row['nama_asrama']))
        //     throw ValidationException::withMessages(["error" => "Invalid headers or missing nama asrama"]);
        // else if (!isset($row['kapasiti']))
        //     throw ValidationException::withMessages(["error" => "Invalid headers or missing kapasiti"]);
        // // else if (!isset($row['bilangan_pelajar_dalam']))
        //     throw ValidationException::withMessages(["error" => "Invalid headers or missing bilangan_pelajar_dalam"]);




        //dd($this->organ);

        if(isset($row['nama_asrama']) && isset($row['kapasiti']))
        {
            $result = DB::table('dorms')->insert([
                'name'      => $row['nama_asrama'],
                'organization_id' => $this->organ,
                'accommodate_no'   => $row['kapasiti'],
                'student_inside_no'    => 0,
            ]);
            // dd($row['nama_asrama']$result);
        }

   
    }
}
