<?php

namespace App\Exports;

use App\Models\Dorm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AllStudentlistExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($organId)
    {
        $this->organId = $organId;
    }

    public function collection()
    {


        // dd($this->organId);
        $studentlist = DB::table('class_student')
            ->join('dorms', 'dorms.id', '=', 'class_student.dorm_id')
            ->join('students', 'students.id', '=', 'class_student.student_id')
            ->join('class_organization as co', 'co.id', '=', 'class_student.organclass_id')
            ->join('classes', 'classes.id', '=', 'co.class_id')
            ->select('students.nama as studentName', 'classes.nama as className', 'dorms.name as dormName', 'class_student.blacklist as blacklist')
            ->selectRaw("(CASE WHEN (blacklist = 1) THEN 'Blacklisted' ELSE 'Not Blacklisted' END) as blacklist")
            ->where([
                //['organization.id', $this->organId],
                ['dorms.organization_id', $this->organId],
            ])
            ->whereNotNull('class_student.dorm_id')
            ->orderBy('students.nama')
            ->get();

           
        return $studentlist;
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Kelas',
            'Dorm',
            'Blacklist Status'
        ];
    }
}
