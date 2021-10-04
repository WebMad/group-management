<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class EduHistoryController extends Controller
{
    public function view()
    {
        return view('history.view');
    }

    public function createReport()
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $students = Student::all();

        $i = 2;
        foreach ($students as $student) {
            $sheet->setCellValue("A{$i}", $student->fio);
            $sheet->getStyle("A{$i}:C{$i}")
                ->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

            $logs = $student->session_log;

            $count_valid_reason = 0;
            $count_not_attend = 0;
            $count = 0;
            foreach ($logs as $log) {
                if ($log->education_history->account_hours && $log->education_history->filled) {
                    $count++;
                    if ($log->valid_reason) {
                        $count_valid_reason++;
                    } elseif (!$log->attend) {
                        $count_not_attend++;
                    }
                }
            }
            $sheet->setCellValue("B{$i}", $count_valid_reason * 2);
            $sheet->setCellValue("C{$i}", $count_not_attend * 2);
            $i++;
        }

        $sheet->setCellValue('A1', 'ФИО');
        $sheet->setCellValue('B1', 'По ув. причине');
        $sheet->setCellValue('C1', 'По неув. причине');

        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THICK,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
        header('Content-Disposition: attachment; filename="' . urlencode('ее.xlsx') . '"');
        $writer->save('php://output');
    }
}
