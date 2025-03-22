<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Document;

class YearlyReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        // Получаем данные за указанный период
        $documents = Document::whereBetween('date', [$this->startDate, $this->endDate])
            ->orderBy('date')
            ->get();

        // Добавляем строку "Итого"
        $totals = $this->withTotals($documents);
        $documents->push($totals);

        return $documents;
    }

    public function headings(): array
    {
        return [
            ['Отчет за период ' . date('Y', strtotime($this->startDate)) . ' - ' . date('Y', strtotime($this->endDate))],
            ['Период', 'Всего отчетов', 'Выполнено', 'В работе', 'Отложены'],
        ];
    }

    public function map($document): array
    {
        // Если это строка "Итого", возвращаем её
        if ($document instanceof \stdClass) {
            return [
                'Итого',
                $document->total,
                $document->completed,
                $document->inWork,
                $document->delayed,
            ];
        }

        // Группируем данные по годам
        $year = date('Y', strtotime($document->date));

        // Считаем количество отчетов по статусам
        $total = Document::whereYear('date', $year)->count();
        $completed = Document::whereYear('date', $year)->where('status', 'Completed')->count();
        $inWork = Document::whereYear('date', $year)->where('status', 'In work')->count();
        $delayed = Document::whereYear('date', $year)->where('status', 'Delayed')->count();

        return [
            $year,
            $total,
            $completed,
            $inWork,
            $delayed,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Стили для заголовков и строки "Итого"
        return [
            1 => ['font' => ['bold' => true]], // Первая строка (заголовок периода)
            2 => ['font' => ['bold' => true]], // Вторая строка (заголовки колонок)
            'A' . ($sheet->getHighestRow()) => ['font' => ['bold' => true]], // Строка "Итого"
        ];
    }

    protected function withTotals($documents)
    {
        $totals = (object) [
            'total' => 0,
            'completed' => 0,
            'inWork' => 0,
            'delayed' => 0,
        ];

        foreach ($documents as $document) {
            $year = date('Y', strtotime($document->date));

            $totals->total += Document::whereYear('date', $year)->count();
            $totals->completed += Document::whereYear('date', $year)->where('status', 'Completed')->count();
            $totals->inWork += Document::whereYear('date', $year)->where('status', 'In work')->count();
            $totals->delayed += Document::whereYear('date', $year)->where('status', 'Delayed')->count();
        }

        return $totals;
    }
}
