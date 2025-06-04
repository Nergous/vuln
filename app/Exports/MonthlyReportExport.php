<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Document;

class MonthlyReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
            ['Отчет за период ' . date('m-Y', strtotime($this->startDate)) . ' - ' . date('m-Y', strtotime($this->endDate))],
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

        // Группируем данные по месяцам
        $month = date('m-Y', strtotime($document->date)); // Формат: месяц-год

        // Считаем количество отчетов по статусам
        $total = Document::whereYear('date', date('Y', strtotime($document->date)))
            ->whereMonth('date', date('m', strtotime($document->date)))
            ->count();
        $completed = Document::whereYear('date', date('Y', strtotime($document->date)))
            ->whereMonth('date', date('m', strtotime($document->date)))
            ->where('status', 'Completed')
            ->count();
        $inWork = Document::whereYear('date', date('Y', strtotime($document->date)))
            ->whereMonth('date', date('m', strtotime($document->date)))
            ->where('status', 'In work')
            ->count();
        $delayed = Document::whereYear('date', date('Y', strtotime($document->date)))
            ->whereMonth('date', date('m', strtotime($document->date)))
            ->where('status', 'Delayed')
            ->count();

        return [
            $month, // Дата в формате mm-yyyy
            $total,
            $completed,
            $inWork,
            $delayed,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:E1');

        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        // Стили для заголовков и строки "Итого"
        return [
            1 => ['font' => ['bold' => true]], // Первая строка (заголовок периода)
            2 => ['font' => ['bold' => true]], // Вторая строка (заголовки колонок)
            'A' . ($sheet->getHighestRow()) => ['font' => ['bold' => true]],
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
            $month = date('m-Y', strtotime($document->date));

            $totals->total += Document::whereYear('date', date('Y', strtotime($document->date)))
                ->whereMonth('date', date('m', strtotime($document->date)))
                ->count();
            $totals->completed += Document::whereYear('date', date('Y', strtotime($document->date)))
                ->whereMonth('date', date('m', strtotime($document->date)))
                ->where('status', 'Completed')
                ->count();
            $totals->inWork += Document::whereYear('date', date('Y', strtotime($document->date)))
                ->whereMonth('date', date('m', strtotime($document->date)))
                ->where('status', 'In work')
                ->count();
            $totals->delayed += Document::whereYear('date', date('Y', strtotime($document->date)))
                ->whereMonth('date', date('m', strtotime($document->date)))
                ->where('status', 'Delayed')
                ->count();
        }

        return $totals;
    }
}
