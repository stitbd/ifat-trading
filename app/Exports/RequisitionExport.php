<?php

namespace App\Exports;

use App\Models\Requisition;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RequisitionExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $data;
    protected $groupedDetails;

    // Total number of columns in the sheet (A to J = 10 columns)
    // SL, Size, Physical Stock, In Transit, LC Pending, PI, Sale1, Sale2, Sale3, Requirement
    protected $totalCols = 10;

    public function __construct(Requisition $data)
    {
        $this->data = $data;

        $this->groupedDetails = $data->details->groupBy(function ($detail) {
            return $detail->product?->category?->name ?? 'Uncategorized';
        });
    }

    public function view(): \Illuminate\Contracts\View\View
    {
        return view('backend.requisitions.export', [
            'data' => $this->data,
            'groupedDetails' => $this->groupedDetails,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $lastColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($this->totalCols);
                $highestRow = $sheet->getHighestRow();

                // ---- 1. Style the main title row (row 1) ----
                $sheet->mergeCells("A1:{$lastColumnLetter}1");
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // ---- 2. Find the "SL" header row dynamically instead of hardcoding ----
                $headerRow = null;
                for ($row = 1; $row <= $highestRow; $row++) {
                    if (trim((string) $sheet->getCell("A{$row}")->getValue()) === 'SL') {
                        $headerRow = $row;
                        break;
                    }
                }

                if ($headerRow) {
                    $headerRange = "A{$headerRow}:{$lastColumnLetter}{$headerRow}";
                    $sheet->getStyle($headerRange)->getFont()->setBold(true);
                    $sheet->getStyle($headerRange)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('F3F3F3');
                    $sheet->getStyle($headerRange)->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                        ->setVertical(Alignment::VERTICAL_CENTER)
                        ->setWrapText(true);

                    // The "REQUIREMENT - FOR ..." section title sits one row above the header
                    $sectionTitleRow = $headerRow - 1;
                    $sheet->mergeCells("A{$sectionTitleRow}:{$lastColumnLetter}{$sectionTitleRow}");
                    $sheet->getStyle("A{$sectionTitleRow}")->getFont()->setBold(true);
                    $sheet->getStyle("A{$sectionTitleRow}")->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // Freeze panes just below the header so it stays visible while scrolling
                    $sheet->freezePane('A' . ($headerRow + 1));
                }

                // ---- 3. Bold + center every "Sub Total" and "Grand Total" row ----
                for ($row = 1; $row <= $highestRow; $row++) {
                    $cellA = trim((string) $sheet->getCell("A{$row}")->getValue());
                    if (in_array($cellA, ['Sub Total', 'Grand Total'])) {
                        $sheet->getStyle("A{$row}:{$lastColumnLetter}{$row}")
                            ->getFont()->setBold(true);
                    }
                }

                // ---- 4. Add borders to the whole used range ----
                $sheet->getStyle("A1:{$lastColumnLetter}{$highestRow}")
                    ->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // ---- 5. Center align all numeric columns (C to J) for readability ----
                if ($headerRow) {
                    $sheet->getStyle("C" . ($headerRow + 1) . ":{$lastColumnLetter}{$highestRow}")
                        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // ---- 6. Set a sensible print/page setup for A4 landscape ----
                $sheet->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4)
                    ->setFitToWidth(1)
                    ->setFitToHeight(0);
            },
        ];
    }
}