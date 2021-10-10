<?php

namespace App\Http\Controllers;

use App\Models\EducationHistory;
use App\Models\Student;
use App\Models\SystemSetting;
use App\Repositories\SystemSettingsRepository;
use DateTime;
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

        $students = Student::all();

        $i = 3;
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

            foreach ($students as $student) {
                $week_attend_count = 0;
                $week_not_attend_count = 0;
                $week_valid_reason = 0;
                /** @var EducationHistory[] $edu_histories */
                $edu_histories = EducationHistory::where('start_date', '<=', $end_date->format('Y-m-d'))->get();
                foreach ($edu_histories as $edu_history) {
                    $week_attend_count += $edu_history->sessionLog()
                        ->where('student_id', $student->id)
                        ->where('attend', true)
                        ->count();
                    $week_not_attend_count += $edu_history->sessionLog()
                        ->where('student_id', $student->id)
                        ->where('attend', false)
                        ->where('valid_reason', false)
                        ->count();
                    $week_valid_reason += $edu_history->sessionLog()
                        ->where('student_id', $student->id)
                        ->where('attend', false)
                        ->where('valid_reason', true)
                        ->count();
                }

                $sheet->getCellByColumnAndRow($x_offset, $y_offset)->setValue($week_valid_reason * 2);
                $sheet->getCellByColumnAndRow($x_offset + 1, $y_offset)->setValue($week_not_attend_count * 2);

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
}
