<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class MainReport implements FromCollection, WithHeadings, WithEvents
{
    protected $start_date, $end_date, $fields, $only_incomplete, $filter_status;

    public function __construct($start_date, $end_date, $fields = [], $only_incomplete = false, $filter_status = '')
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->fields = $fields;
        $this->only_incomplete = $only_incomplete;
        $this->filter_status = $filter_status;
    }

    public function collection()
    {
        $query = DB::table('documents')
            ->select(
                'documents.id',
                'documents.number',
                'documents.name as document_name',
                'documents.date as document_date',
                'documents.status as document_status'
            )
            ->whereBetween('documents.date', [$this->start_date, $this->end_date]);

        if ($this->only_incomplete) {
            $query->where('documents.status', '!=', 'Completed');
        }

        if ($this->filter_status) {
            $query->where('documents.status', $this->filter_status);
        }

        $results = $query->get();

        $finalResults = collect();
        $statusTranslations = [
            'Completed' => 'Завершен',
            'In work' => 'В работе',
            'Delayed' => 'Отложен'
        ];

        foreach ($results as $key => $item) {
            $documentData = [
                'serial_number' => $key + 1,
                'document_number' => $item->number,
                'document_name' => $item->document_name,
                'document_date' => $item->document_date,
                'status' => isset($statusTranslations[$item->document_status]) ? $statusTranslations[$item->document_status] : $item->document_status,
            ];

            if (in_array('delayed_reason', $this->fields)) {
                $delayed_reason = DB::table('delayed_documents')
                    ->where('document_id', $item->id)
                    ->value('reason');

                $documentData['delayed_reason'] = $delayed_reason ?? '';
            }

            $hasVulnerabilities = false;

            if (in_array('vulnerability_code', $this->fields) || in_array('vulnerability_name', $this->fields)) {
                $vulnerabilities = DB::table('document_vulnerability')
                    ->join('vulnerabilities', 'document_vulnerability.vulnerability_id', '=', 'vulnerabilities.id')
                    ->where('document_vulnerability.document_id', $item->id)
                    ->select(
                        'vulnerabilities.id',
                        'vulnerabilities.code as vulnerability_code',
                        'vulnerabilities.name as vulnerability_name'
                    )
                    ->get();

                foreach ($vulnerabilities as $vuln) {
                    $hasVulnerabilities = true;
                    $vulnData = array_merge($documentData, [
                        'vulnerability_code' => $vuln->vulnerability_code,
                        'vulnerability_name' => $vuln->vulnerability_name,
                    ]);
                    // initialize object with compensating_solutions and real_solutions
                    $solutions = [];
                    $isNotUsed = DB::table('vulnerabilities')
                        ->where('id', $vuln->id)
                        ->value('not_used');
                    if ($isNotUsed == 1) {
                        $vulnData['real_solutions'] = "Не используется в обособленном подразделении";
                        $vulnData['compensating_solutions'] = "Не используется в обособленном подразделении";
                        $finalResults->push($vulnData);
                        continue;
                    }

                    if (in_array('real_solutions', $this->fields)) {
                        $real_solutions = DB::table('vulnerability_real_solution')
                            ->join('real_solutions', 'vulnerability_real_solution.real_solution_id', '=', 'real_solutions.id')
                            ->where('vulnerability_real_solution.vulnerability_id', $vuln->id)
                            ->pluck('real_solutions.solution')
                            ->join('; '); // Объединяем решения в одну строку через ';'
                        // Добавляем решения к результату
                        if ($real_solutions !== null) {
                            $solutions['real_solutions'] = $real_solutions;
                        }
                    } else {
                        $finalResults->push($vulnData);
                    }
                    if (in_array('compensating_solutions', $this->fields)) {
                        $compensating_solutions = DB::table('vulnerability_compensating_solution')
                            ->join('compensating_solutions', 'vulnerability_compensating_solution.compensating_solution_id', '=', 'compensating_solutions.id')
                            ->where('vulnerability_compensating_solution.vulnerability_id', $vuln->id)
                            ->pluck('compensating_solutions.measure')
                            ->join('; '); // Объединяем решения в одну строку через ';'
                        // Добавляем решения к результату
                        if ($compensating_solutions !== null) {
                            $solutions['compensating_solutions'] = $compensating_solutions;
                        }
                    } else {
                        $finalResults->push($vulnData);
                    }
                    $vulnData = array_merge($vulnData, $solutions);
                    $finalResults->push($vulnData);
                }
            }
            if (!$hasVulnerabilities) {
                $finalResults->push($documentData);
            }
        }

        $finalResults = $finalResults->map(function ($item, $key) use ($finalResults) {
            if ($key > 0) {
                $previous = $finalResults[$key - 1];

                if (
                    $item['document_number'] === $previous['document_number'] &&
                    $item['document_name'] === $previous['document_name'] &&
                    $item['document_date'] === $previous['document_date']
                ) {
                    $item['document_number'] = '';
                    $item['document_name'] = '';
                    $item['document_date'] = '';
                    $item['status'] = '';
                }
            }

            return $item;
        });
        // Фильтруем по выбранным полям и добавляем их в итоговый результат
        $alwaysIncludedFields = ['serial_number', 'document_number', 'document_name', 'document_date', 'status'];
        $selectedFields = array_unique(array_merge($this->fields, $alwaysIncludedFields));

        $finalResults = $finalResults->map(function ($item) use ($selectedFields) {
            return array_intersect_key((array) $item, array_flip($selectedFields));
        });
        return new Collection($finalResults);
    }

    public function headings(): array
    {
        $defaultHeadings = [
            'serial_number' => 'п.н.',
            'document_number' => 'Номер письма',
            'document_name' => 'Наименование письма',
            'document_date' => 'Дата',
            'document_status' => 'Статус письма',
            'delayed_reason' => 'Причина отложки',
            'vulnerability_code' => 'Код уязвимости / задачи',
            'vulnerability_name' => 'Наименование уязвимости / задачи',
            'real_solutions' => 'Реальные решения',  // Переименованный столбец
            'compensating_solutions' => 'Компенсирующие решения',  // Новый столбец
        ];

        $alwaysIncludedFields = ['serial_number', 'document_number', 'document_name', 'document_date', 'document_status'];
        $selectedFields = array_unique(array_merge($alwaysIncludedFields, $this->fields));

        // Убираем 'vulnerability_status' из выбранных полей
        $selectedFields = array_filter($selectedFields, function ($field) {
            return $field !== 'vulnerability_status';
        });

        return array_map(function ($field) use ($defaultHeadings) {
            return $defaultHeadings[$field];
        }, $selectedFields);
    }


    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function (BeforeWriting $event) {
                $spreadsheet = $event->getWriter()->getDelegate();
                $sheet = $spreadsheet->getActiveSheet();

                $rowIndex = 2;
                $lastRow = $sheet->getHighestRow();
                $lastColumn = Coordinate::columnIndexFromString($sheet->getHighestColumn());

                $mergeGroups = [];
                $currentGroup = null;

                for (; $rowIndex <= $lastRow; $rowIndex++) {
                    $serialNumber = $sheet->getCell("A{$rowIndex}")->getValue();
                    $htmlText = $sheet->getCell("I{$rowIndex}")->getValue();

                    if ($htmlText) {
                        $cleanText = strip_tags($htmlText);
                        $sheet->getCell("I{$rowIndex}")->setValue($cleanText);
                    }

                    if (!$currentGroup || $currentGroup['value'] !== $serialNumber) {
                        if ($currentGroup && $currentGroup['start'] !== $currentGroup['end']) {
                            $mergeGroups[] = $currentGroup;
                        }
                        $currentGroup = [
                            'value' => $serialNumber,
                            'start' => $rowIndex,
                            'end' => $rowIndex
                        ];
                    } else {
                        $currentGroup['end'] = $rowIndex;
                    }
                }

                if ($currentGroup && $currentGroup['start'] !== $currentGroup['end']) {
                    $mergeGroups[] = $currentGroup;
                }

                foreach ($mergeGroups as $group) {
                    $start = $group['start'];
                    $end = $group['end'];

                    foreach (range('A', 'F') as $column) {
                        $sheet->mergeCells("{$column}{$start}:{$column}{$end}");
                    }
                }

                // Стилизация заголовков
                $headerStyle = [
                    'font' => ['bold' => true, 'name' => 'Consolas'], // Установлен шрифт Consolas
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];
                $sheet->getStyle("A1:" . Coordinate::stringFromColumnIndex($lastColumn) . "1")->applyFromArray($headerStyle);

                $allBordersStyle = [
                    'font' => ['name' => 'Consolas'], 
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];
                $sheet->getStyle("A1:" . Coordinate::stringFromColumnIndex($lastColumn) . $lastRow)->applyFromArray($allBordersStyle);

                // Ограничение ширины столбцов, автоперенос текста
                for ($col = 1; $col <= $lastColumn; $col++) {
                    $columnLetter = Coordinate::stringFromColumnIndex($col);
                    $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
                    if ($columnLetter === 'C' || $columnLetter === 'G') {
                        $sheet->getColumnDimension($columnLetter)->setAutoSize(false);
                        $sheet->getColumnDimension($columnLetter)->setWidth(40); // Можно изменить на нужную ширину
                    }
                    if ($columnLetter === 'J') {
                        $sheet->getColumnDimension($columnLetter)->setAutoSize(false);
                        $sheet->getColumnDimension($columnLetter)->setWidth(50);
                    }

                    $sheet->getStyle("{$columnLetter}1:{$columnLetter}{$lastRow}")->getAlignment()->setWrapText(true);
                }

                // Автоподстройка высоты строк
                for ($row = 1; $row <= $lastRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(-1);
                }
            },
        ];
    }
}
