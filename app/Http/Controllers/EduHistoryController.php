<?php

namespace App\Http\Controllers;

use App\Models\EducationHistory;
use App\Models\Student;
use App\Models\SystemSetting;
use App\Repositories\SystemSettingsRepository;
use DateTime;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
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

        $students = Student::get()->sortBy('surname');

        $i = 3;
        foreach ($students as $student) {
            $sheet->setCellValue("A{$i}", "{$student->surname} {$student->name} {$student->patronymic}");
            $sheet->getStyle("A{$i}:C{$i}")
                ->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);
            $i++;
        }

        $sheet->mergeCells('A1:A2');
        $sheet->setCellValue('A1', 'ФИО');

        $start_date = new DateTime(SystemSettingsRepository::getSetting(SystemSetting::START_YEAR_SETTING));
        $end_date = new DateTime(SystemSettingsRepository::getSetting(SystemSetting::START_YEAR_SETTING));

        $num_week = 1;

        $x_offset = 2;
        $y_offset = 3;
        while ($end_date < new DateTime()) {
            $y_offset = 3;
            while ($end_date->format('w') != 0) {
                $end_date->modify('+1 day');
            }

            $start_merge = $sheet
                ->getCellByColumnAndRow($x_offset, $y_offset - 2)
                ->getCoordinate();
            $end_merge = $sheet
                ->getCellByColumnAndRow($x_offset+1, $y_offset - 2)
                ->getCoordinate();

            $sheet->mergeCells("$start_merge:$end_merge");

            $sheet->getColumnDimensionByColumn($x_offset)->setWidth(16);
            $sheet->getColumnDimensionByColumn($x_offset + 1)->setWidth(16);

            $sheet->getCellByColumnAndRow($x_offset, $y_offset - 2)
                ->setValue($start_date->format('d.m.Y')
                    . ' - ' . $end_date->format('d.m.Y')
                    . "\n"
                    . "$num_week неделя"
                )
                ->getStyle()
                ->getAlignment()
                ->setWrapText(true);

            $sheet->getCellByColumnAndRow($x_offset, $y_offset - 1)
                ->setValue('по ув. причине');

            $sheet->getCellByColumnAndRow($x_offset + 1, $y_offset - 1)
                ->setValue('по неув. причине');

            $students = $this->getHistory((clone $end_date)->modify('+1 day')->format('Y-m-d'));

            foreach ($students as $student) {
                $sheet->getCellByColumnAndRow($x_offset, $y_offset)->setValue($student->valid * 2);
                $sheet->getCellByColumnAndRow($x_offset + 1, $y_offset)->setValue($student->not_valid * 2);

                $y_offset++;
            }

            $start_date = clone $end_date;
            $start_date->modify('+1 day');
            $end_date->modify('+1 day');
            $x_offset += 2;
            $num_week++;
        }

        $start_border = $sheet->getCellByColumnAndRow(1, 1)->getCoordinate();
        $end_border = $sheet->getCellByColumnAndRow($x_offset - 1, $y_offset - 1)->getCoordinate();

        $sheet->getStyle("$start_border:$end_border")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ]);

        $start_align = $sheet->getCellByColumnAndRow(1, 1)->getCoordinate();
        $end_align = $sheet->getCellByColumnAndRow($x_offset - 1, 2)->getCoordinate();

        $borders = $sheet->getStyle("$start_align:$end_align")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ])->getBorders();

        $borders->getBottom()->setBorderStyle(Border::BORDER_THICK);
        $borders->getTop()->setBorderStyle(Border::BORDER_THICK);
        $borders->getLeft()->setBorderStyle(Border::BORDER_THICK);
        $borders->getRight()->setBorderStyle(Border::BORDER_THICK);

        $sheet->getColumnDimension('A')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
        header('Content-Disposition: attachment; filename="' . urlencode((new DateTime())->format('d.m.Y H:i:s') . '.xlsx') . '"');
        $writer->save('php://output');
    }

    private function getHistory($to = '') {
        return DB::table('students as s')
            ->select([
                's.name',
                's.surname',
                's.patronymic',
                new Expression('count(sl.id) as not_valid'),
                new Expression('count(sl2.id) as valid'),
            ])
            ->leftJoin('education_history as eh', function (JoinClause $join) use ($to) {
                $join
                    ->on('eh.start_date', '<=', new Expression("'{$to}'"))
                    ->on('eh.filled', '=', new Expression('true'))
                    ->on('eh.account_hours', '=', new Expression('true'));
            })
            ->leftJoin('session_log as sl', function (JoinClause $join) {
                $join
                    ->on('eh.id', '=', 'sl.eh_id')
                    ->on('sl.student_id', '=', 's.id')
                    ->on('sl.valid_reason', '=', new Expression('false'))
                    ->on('sl.attend', '=', new Expression('false'));
            })
            ->leftJoin('session_log as sl2', function (JoinClause $join) {
                $join
                    ->on('eh.id', '=', 'sl2.eh_id')
                    ->on('sl2.student_id', '=', 's.id')
                    ->on('sl2.valid_reason', '=', new Expression('true'))
                    ->on('sl2.attend', '=', new Expression('false'));
            })
            ->orderBy('s.surname')
            ->groupBy('s.name', 's.surname', 's.patronymic')
            ->get();
    }
}
