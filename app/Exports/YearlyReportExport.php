<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class YearlyReportExport implements FromCollection, WithHeadings
{
    protected $start_date, $end_date, $fields;

    public function __construct($start_date, $end_date, $fields = [])
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->fields = $fields;
    }

    public function collection()
    {
        $results = DB::table('documents')
            ->select(
                'documents.id', // Добавляем id в выборку
                'documents.name as document_name',
                'documents.date as document_date',
                'documents.status as status'
            )
            ->whereBetween('documents.date', [$this->start_date, $this->end_date])
            ->get();

        $finalResults = collect();

        foreach ($results as $item) {
            $documentData = [
                'document_name' => $item->document_name,
                'document_date' => $item->document_date,
                'status' => $item->status,
            ];

            if (in_array('delayed_reason', $this->fields)) {
                $delayed_reason = DB::table('delayed_documents')
                    ->where('document_id', $item->id)
                    ->value('reason');

                if ($delayed_reason !== null) {
                    $documentData['delayed_reason'] = $delayed_reason;
                } else {
                    $documentData['delayed_reason'] = ''; // или любое другое значение по умолчанию
                }
            }

            $hasVulnerabilities = false;

            if (in_array('vulnerability_code', $this->fields) || in_array('vulnerability_name', $this->fields)) {
                $vulnerabilities = DB::table('document_vulnerability')
                    ->join('vulnerabilities', 'document_vulnerability.vulnerability_id', '=', 'vulnerabilities.id')
                    ->where('document_vulnerability.document_id', $item->id)
                    ->select('vulnerabilities.id', 'vulnerabilities.code as vulnerability_code', 'vulnerabilities.name as vulnerability_name')
                    ->get();

                foreach ($vulnerabilities as $vuln) {
                    $hasVulnerabilities = true;
                    $vulnData = array_merge($documentData, [
                        'vulnerability_code' => $vuln->vulnerability_code,
                        'vulnerability_name' => $vuln->vulnerability_name,
                    ]);

                    if (in_array('solution', $this->fields) || in_array('solution_type', $this->fields)) {
                        $solutions = collect();

                        $compensating_solutions = DB::table('vulnerability_compensating_solution')
                            ->join('compensating_solutions', 'vulnerability_compensating_solution.compensating_solution_id', '=', 'compensating_solutions.id')
                            ->where('vulnerability_compensating_solution.vulnerability_id', $vuln->id)
                            ->select('compensating_solutions.measure as solution', DB::raw('"Компенсирующее решение" as solution_type'))
                            ->get();

                        $real_solutions = DB::table('vulnerability_real_solution')
                            ->join('real_solutions', 'vulnerability_real_solution.real_solution_id', '=', 'real_solutions.id')
                            ->where('vulnerability_real_solution.vulnerability_id', $vuln->id)
                            ->select('real_solutions.solution as solution', DB::raw('"Реальное решение" as solution_type'))
                            ->get();

                        $solutions = $solutions->concat($compensating_solutions)->concat($real_solutions);

                        foreach ($solutions as $solution) {
                            $finalResults->push(array_merge($vulnData, [
                                'solution' => $solution->solution,
                                'solution_type' => $solution->solution_type,
                            ]));
                        }

                        if ($solutions->isEmpty()) {
                            $finalResults->push($vulnData);
                        }
                    } else {
                        $finalResults->push($vulnData);
                    }
                }
            }

            if (!$hasVulnerabilities) {
                $finalResults->push($documentData);
            }
        }

        // Убираем поля, которые не были выбраны, но всегда оставляем document_name, document_date и status
        $alwaysIncludedFields = ['document_name', 'document_date', 'status'];
        $selectedFields = array_unique(array_merge($this->fields, $alwaysIncludedFields));

        $finalResults = $finalResults->map(function ($item) use ($selectedFields) {
            return array_intersect_key((array) $item, array_flip($selectedFields));
        });

        return new Collection($finalResults);
    }

    public function headings(): array
    {
        $defaultHeadings = [
            'document_name' => 'Название документа',
            'document_date' => 'Дата',
            'status' => 'Статус',
            'delayed_reason' => 'Причина отложки',
            'vulnerability_code' => 'Код уязвимости',
            'vulnerability_name' => 'Название уязвимости',
            'solution' => 'Решение',
            'solution_type' => 'Тип решения',
        ];

        // Всегда включаем document_name, document_date и status
        $alwaysIncludedFields = ['document_name', 'document_date', 'status'];
        $selectedFields = array_unique(array_merge($alwaysIncludedFields, $this->fields));

        return array_map(function ($field) use ($defaultHeadings) {
            return $defaultHeadings[$field];
        }, $selectedFields);
    }
}