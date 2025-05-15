<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CommonExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    protected $dataList;
    protected $header;
    protected $subHeader;
    protected $footer;
    protected $lastRow = 1;
    protected $sheetTitle;
    public function __construct(array $dataList, $header, $subHeader = [], $footer = [], $sheetTitle = '')
    {
        $this->dataList = $dataList;
        $this->header = $header;
        $this->subHeader = $subHeader;
        $this->footer = $footer;
        $this->sheetTitle = $sheetTitle;
        if (count($this->header) > 0) {
            // if header set, footer as second(2nd) row
            $this->lastRow++;
        }
        if (count($this->subHeader) > 0) {
            // if sub header set, footer as third(3rd) row
            $this->lastRow++;
        }
        if (count($this->dataList) > 0) {
            // if data set, footer as total data + header(1) + subHeader(1) count
            $this->lastRow = $this->lastRow + count($this->dataList);
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     * return multidimentsion array
     */
    // use Exportable;

    public function headings(): array
    {
        $returnHeader = [];
        //header map by name
        $headerValues = collect($this->header);
        $headerValues = $headerValues->map(function ($item, $key) {
            return $item['name'];
        })->toArray();
        $returnHeader[] = $headerValues;
        if (count($this->subHeader) > 0) {
            $returnHeader[] = $this->subHeader;
        }
        return $returnHeader;
    }

    /**
     * @return \Illuminate\Support\Collection
     * return array which to print in excel
     */
    function array(): array
    {
        return $this->dataList;
    }

    /**
     * @return \Illuminate\Support\Collection
     * return array which to print in excel
     */
    public function styles(Worksheet $sheet)
    {
        // for header and sub header formatting
        if (count($this->header) > 0) {
            $rowValue = 1;
            $colValue = 0;
            foreach ($this->header as $key => $value) {
                if ($value['rowSpan'] > 1) {
                    $column = $this->toAlpha($colValue);
                    $upToRow = $rowValue + $value['rowSpan'] - 1;
                    $sheet->mergeCells("$column$rowValue:$column$upToRow");
                    $sheet->getStyle("$column$rowValue:$column$upToRow")->getAlignment()
                        ->setVertical('center')->setHorizontal('center');
                }
                if ($value['colSpan'] > 1) {
                    $columnStart = $this->toAlpha($colValue);
                    $columnEnd = $this->toAlpha($colValue + $value['colSpan'] - 1);
                    $sheet->mergeCells("$columnStart$rowValue:$columnEnd$rowValue");
                    $sheet->setCellValue("$columnStart$rowValue", $value['name']);
                    $sheet->getStyle("$columnStart$rowValue:$columnEnd$rowValue")->getAlignment()
                        ->setVertical('center')->setHorizontal('center');
                    $colValue = $colValue + $value['colSpan'] - 1;
                    $colValue++;
                } else {
                    $getColumn = $this->toAlpha($colValue);
                    $sheet->setCellValue("$getColumn$rowValue", $value['name']);
                    $colValue++;
                }
            }
        }
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFont()->setSize(12);
        $sheet->getStyle('1')->getAlignment()->setHorizontal('center');
        if (count($this->subHeader) > 0) {
            $sheet->getStyle('2')->getFont()->setBold(true);
            $sheet->getStyle('2')->getAlignment()->setHorizontal('center');
        }
        // for footer formatting
        if (count($this->header) > 0) {
            $rowValue = $this->lastRow;
            $colValue = 0;
            foreach ($this->footer as $key => $value) {
                if ($value['rowSpan'] > 1) {
                    $column = $this->toAlpha($key);
                    $upToRow = $rowValue + $value['rowSpan'] - 1;
                    $sheet->mergeCells("$column$rowValue:$column$upToRow");
                    $sheet->getStyle("$column$rowValue:$column$upToRow")->getAlignment()
                        ->setVertical('center')->setHorizontal('center');
                }
                if ($value['colSpan'] > 1) {
                    $columnStart = $this->toAlpha($colValue);
                    $columnEnd = $this->toAlpha($colValue + $value['colSpan'] - 1);
                    $sheet->mergeCells("$columnStart$rowValue:$columnEnd$rowValue");
                    $getColumn = $this->toAlpha($colValue);
                    $sheet->setCellValue("$getColumn$rowValue", $value['name']);
                    $sheet->getStyle("$columnStart$rowValue:$columnEnd$rowValue")->getAlignment()
                        ->setVertical('center')->setHorizontal('center');
                    $colValue = $colValue + $value['colSpan'] - 1;
                } else {
                    $colValue++;
                    $getColumn = $this->toAlpha($colValue);
                    $sheet->setCellValue("$getColumn$rowValue", $value['name']);
                }
            }
            $sheet->getStyle("$this->lastRow")->getAlignment()->setHorizontal('center');
            $sheet->getStyle("$this->lastRow")->getFont()->setBold(true);
        }
    }

    public function title(): string
    {
        return $this->sheetTitle;
    }

    protected function toAlpha($int_value)
    {
        $alphabet = range('A', 'Z');
        $alpha_flip = array_flip($alphabet);
        if ($int_value <= 25) {
            return $alphabet[$int_value];
        } elseif ($int_value > 25) {
            $dividend = ($int_value + 1);
            $alpha = $modulo = '';
            while ($dividend > 0) {
                $modulo = ($dividend - 1) % 26;
                $alpha = $alphabet[$modulo] . $alpha;
                $dividend = floor((($dividend - $modulo) / 26));
            }
            return $alpha;
        }
    }
}
