<?php

namespace App\Http\Controllers;

use App\Models\DelayedDocument;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Exports\YearlyReportExport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class ReportController extends Controller
{
    public function create()
    {
        return view('report.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'date' => 'required|date',
        'number' => 'required',
        'vulnerabilities' => 'array',
        'vulnerabilities.*.name' => 'required',
        'vulnerabilities.*.code' => 'nullable',
        'vulnerabilities.*.software' => 'nullable',
        'vulnerabilities.*.status' => 'required|in:High,Middle,Low',
        'stamp_high_date' => 'nullable|date',
        'stamp_high_number' => 'nullable|unique:documents,NULL,NULL,id,stamp_high_date,' . $request->stamp_high_date,
        'stamp_low_date' => 'nullable|date',
        'stamp_low_number' => 'nullable|unique:documents,NULL,NULL,id,stamp_low_date,' . $request->stamp_low_date,
        'file' => 'required|file|mimes:doc,docx,pdf', // Проверка на тип файла
    ]);

    // Сохранение файла
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filePath = $file->store('documents'); // Сохраняем файл в папку 'documents'
    }

    $document = Document::create([
        'name' => $request->name,
        'date' => $request->date,
        'number' => $request->number,
        'status' => 'In work', // Автоматически устанавливаем статус
        'stamp_high_date' => $request->stamp_high_date,
        'stamp_high_number' => $request->stamp_high_number,
        'stamp_low_date' => $request->stamp_low_date,
        'stamp_low_number' => $request->stamp_low_number,
        'path_to_file' => $filePath, // Сохраняем путь к файлу в базе данных
    ]);

    // Обработка уязвимостей, если они есть
    if ($request->has('vulnerabilities')) {
        foreach ($request->vulnerabilities as $vulnerability) {
            $document->vulnerabilities()->create([
                'name' => $vulnerability['name'],
                'code' => $vulnerability['code'],
                'software' => $vulnerability['software'],
                'status' => $vulnerability['status'],
                'complete_status' => 'In work',
            ]);
        }
    }

    return redirect()->route('home')->with('success', 'Отчет успешно добавлен!');
}

    public function edit($id)
    {
        $document = Document::with('vulnerabilities')->findOrFail($id);
        return view('report.edit', compact('document'));
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        $document->delete();
        return redirect()->route('home')->with('success', 'Отчет успешно удален!');
    }

    public function update(Request $request, $id)
{
    $document = Document::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'date' => 'required|date',
        'number' => 'required',
        'vulnerabilities' => 'array',
        'vulnerabilities.*.name' => 'required',
        'vulnerabilities.*.code' => 'nullable',
        'vulnerabilities.*.software' => 'nullable',
        'vulnerabilities.*.status' => 'required|in:High,Middle,Low',
        'stamp_high_date' => 'nullable|date',
        'stamp_high_number' => 'nullable|unique:documents,stamp_high_number,' . $id . ',id,stamp_high_date,' . $request->stamp_high_date,
        'stamp_low_date' => 'nullable|date',
        'stamp_low_number' => 'nullable|unique:documents,stamp_low_number,' . $id . ',id,stamp_low_date,' . $request->stamp_low_date,
        'file' => 'nullable|file|mimes:doc,docx,pdf', // Проверка на тип файла
    ]);

    // Обработка файла
    if ($request->hasFile('file')) {
        // Удаление старого файла, если он существует
        if ($document->path_to_file) {
            Storage::delete($document->path_to_file);
        }

        // Сохранение нового файла
        $file = $request->file('file');
        $filePath = $file->store('documents'); // Сохраняем файл в папку 'documents'
        $document->path_to_file = $filePath;
    }

    $document->update([
        'name' => $request->name,
        'date' => $request->date,
        'number' => $request->number,
        'stamp_high_date' => $request->stamp_high_date,
        'stamp_high_number' => $request->stamp_high_number,
        'stamp_low_date' => $request->stamp_low_date,
        'stamp_low_number' => $request->stamp_low_number,
        'path_to_file' => $document->path_to_file, // Обновляем путь к файлу, если он был изменен
    ]);

    // Обработка уязвимостей
    if ($request->has('vulnerabilities')) {
        $vulnerabilities = collect($request->vulnerabilities);
        $document->vulnerabilities()->each(function ($vulnerability) use ($vulnerabilities) {
            if (!$vulnerabilities->contains('name', $vulnerability->name)) {
                $vulnerability->delete();
            }
        });

        foreach ($request->vulnerabilities as $vulnerability) {
            $document->vulnerabilities()->updateOrCreate(
                ['name' => $vulnerability['name']],
                [
                    'code' => $vulnerability['code'],
                    'software' => $vulnerability['software'],
                    'status' => $vulnerability['status'],
                ]
            );
        }
    } else {
        $document->vulnerabilities()->delete();
    }

    return redirect()->route('home')->with('success', 'Отчет успешно обновлен!');
}

    public function show($id)
    {
        $document = Document::with('vulnerabilities')->findOrFail($id);
        return view('report.all_vulnerabilites', compact('document'));
    }

    public function exportYearlyReport(Request $request)
    {
        $start_date = $request->input('start_date', date('YYYY-mm-dd')); // Получаем год из запроса или используем текущий год по умолчанию
        $end_date = $request->input('end_date', date('YYYY-mm-dd')); // Получаем год из запроса или используем текущий год по умолчанию
        $fields = $request->input('fields', []);
        return Excel::download(new YearlyReportExport($start_date, $end_date, $fields), 'report.xlsx');
    }

    public function delay($id)
    {
        return view('report.change_delay', compact('id'));
    }

    public function createDelay(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'reason' => 'required',
        ]);
    
        $date = $request->date;
        $reason = $request->reason;

        $document = Document::findOrFail($id);
        $document->update([
            'status' => 'Delayed',
        ]);
        DelayedDocument::where('document_id', $id)->delete();
        DelayedDocument::create([
            'document_id' => $id,
            'delayed_date' => $date,
            'reason' => $reason,
        ]);
        return redirect()->route('home')->with('success', 'Отчет успешно отложен!');
    }


}