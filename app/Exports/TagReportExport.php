<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class TagReportExport implements FromCollection, WithHeadings, WithEvents
{
    protected $tagIds;

    public function __construct(array $tagIds)
    {
        $this->tagIds = $tagIds;
    }

    public function collection()
    {
        $documents = DB::table('documents')
            ->leftJoin('document_tags', 'documents.id', '=', 'document_tags.document_id')
            ->leftJoin('tags', 'tags.id', '=', 'document_tags.tags_id')
            ->leftJoin('delayed_documents', 'delayed_documents.document_id', '=', 'documents.id')
            ->whereIn('document_tags.tags_id', $this->tagIds)
            ->groupBy('documents.id', 'documents.number', 'documents.name', 'documents.date', 'documents.status', 'delayed_documents.reason')
            ->select(
                'documents.id',
                'documents.number as document_number',
                'documents.name as document_name',
                'documents.date as document_date',
                'documents.status as document_status',
                DB::raw("GROUP_CONCAT(DISTINCT tags.name SEPARATOR '; ') AS tags"),
                'delayed_documents.reason'
            )
            ->get();

        $statusTranslations = [
            'Completed' => 'Завершен',
            'In work' => 'В работе',
            'Delayed' => 'Отложен'
        ];

        // Добавьте эту строку для перевода статусов
        $documents->transform(function ($document) use ($statusTranslations) {
            $document->document_status = $statusTranslations[$document->document_status] ?? $document->document_status;
            return $document;
        });

        return $documents;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Номер документа',
            'Название документа',
            'Дата',
            'Статус документа',
            'Тэги',
            'Причина отложки'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Настройка заголовков
                $headerStyle = [
                    'font' => ['name' => 'Consolas', 'bold' => true],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
                    ]
                ];
                $sheet->getStyle("A1:{$highestColumn}1")->applyFromArray($headerStyle);

                // Настройка всего листа
                $cellStyle = [
                    'font' => ['name' => 'Consolas'],
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ]
                ];
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray($cellStyle);

                // Ширина столбцов
                $sheet->getColumnDimension('C')->setWidth(40); // Название документа
                $sheet->getColumnDimension('F')->setWidth(50); // Теги
                $sheet->getColumnDimension('G')->setWidth(30); // Причина отложки

                // Автоподстройка остальных столбцов
                foreach (range('A', $highestColumn) as $col) {
                    if (!in_array($col, ['C', 'F', 'G'])) {
                        $sheet->getColumnDimension($col)->setAutoSize(true);
                    }
                }

                // Автоперенос текста
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")
                    ->getAlignment()
                    ->setWrapText(true);

                // Фильтр
                $sheet->setAutoFilter("A1:{$highestColumn}{$highestRow}");
            }
        ];
    }
}
