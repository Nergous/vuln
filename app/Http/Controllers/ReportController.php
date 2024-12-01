<?php

namespace App\Http\Controllers;

use App\Models\DelayedDocument;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Status;
use App\Exports\YearlyReportExport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Tags;


class ReportController extends Controller
{
    public function create()
    {
        $statuses = Status::all();
        $tags = Tags::all();
        return view('report.create', compact('statuses', 'tags'));
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
            'vulnerabilities.*.status' => 'required', // Убираем проверку на enum
            'stamp_high_date' => 'nullable|date',
            'stamp_high_number' => 'nullable|unique:documents,NULL,NULL,id,stamp_high_date,' . $request->stamp_high_date,
            'stamp_low_date' => 'nullable|date',
            'stamp_low_number' => 'nullable|unique:documents,NULL,NULL,id,stamp_low_date,' . $request->stamp_low_date,
            'file' => 'nullable|file|mimes:doc,docx,pdf', // Проверка на тип файла
            'tags' => 'nullable|array',
        ]);

        $filePath = null;
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

        $tags = [];
        if ($request->has('tags')) {
            foreach ($request->tags as $tagNum => $tagName) {
                $tag = Tags::where('name', $tagName)->first();
                if ($tag) {
                    $tags[] = $tag->id;
                } else {
                    $newTag = Tags::firstOrCreate(['name' => $tagName]);
                    $tags[] = $newTag->id;
                }
            }
        }

        // Обработка уязвимостей, если они есть
        if ($request->has('vulnerabilities')) {
            foreach ($request->vulnerabilities as $vulnerability) {
                $status = Status::where('id', $vulnerability['status'])->first(); // Ищем статус по имени
                $document->vulnerabilities()->create([
                    'name' => $vulnerability['name'],
                    'code' => $vulnerability['code'],
                    'software' => $vulnerability['software'],
                    'status_id' => $status->id, // Используем ID статуса
                    'complete_status' => 'In work',
                ]);
            }
        }

        if (!empty($tags)) {
            $document->tags()->sync($tags);
        }

        return redirect()->route('home')->with('success', 'Отчет успешно добавлен!');
    }

    public function edit($id)
    {
        $document = Document::with('vulnerabilities', 'tags')->findOrFail($id);
        $statuses = Status::all();
        $tags = Tags::all();
        return view('report.edit', compact('document', 'statuses', 'tags'));
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
            'vulnerabilities.*.status' => 'required', // Убираем проверку на enum
            'stamp_high_date' => 'nullable|date',
            'stamp_high_number' => 'nullable|unique:documents,stamp_high_number,' . $id . ',id,stamp_high_date,' . $request->stamp_high_date,
            'stamp_low_date' => 'nullable|date',
            'stamp_low_number' => 'nullable|unique:documents,stamp_low_number,' . $id . ',id,stamp_low_date,' . $request->stamp_low_date,
            'file' => 'nullable|file|mimes:doc,docx,pdf', // Проверка на тип файла
            'tags' => 'nullable|array',
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

        $tags = [];
        if ($request->has('tags')) {
            foreach ($request->tags as $tagNum => $tagName) {
                $tag = Tags::where('name', $tagName)->first();
                if ($tag) {
                    $tags[] = $tag->id;
                } else {
                    $newTag = Tags::firstOrCreate(['name' => $tagName]);
                    $tags[] = $newTag->id;
                }
            }
        }

        // Обработка уязвимостей
        if ($request->has('vulnerabilities')) {
            $vulnerabilities = collect($request->vulnerabilities);
            $document->vulnerabilities()->each(function ($vulnerability) use ($vulnerabilities) {
                if (!$vulnerabilities->contains('name', $vulnerability->name)) {
                    $vulnerability->delete();
                }
            });

            foreach ($request->vulnerabilities as $vulnerability) {
                $status = Status::where('id', $vulnerability['status'])->first(); // Ищем статус по имени
                $document->vulnerabilities()->updateOrCreate(
                    ['name' => $vulnerability['name']],
                    [
                        'code' => $vulnerability['code'],
                        'software' => $vulnerability['software'],
                        'status_id' => $status->id, // Используем ID статуса
                        'complete_status' => 'In work',
                    ]
                );
            }
        } else {
            $document->vulnerabilities()->delete();
        }
        if (!empty($tags)) {
            $document->tags()->sync($tags);
        }

        return redirect()->route('home')->with('success', 'Отчет успешно обновлен!');
    }

    public function show($id)
    {
        $document = Document::with('vulnerabilities')->findOrFail($id);
        $statuses = Status::all();
        return view('report.all_vulnerabilites', compact('document', 'statuses'));
    }

    public function exportYearly(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $fields = $request->input('fields', []);
        $onlyIncomplete = $request->input('only_incomplete', false);
        $filterStatus = $request->input('filter_status', '');

        return Excel::download(new YearlyReportExport($startDate, $endDate, $fields, $onlyIncomplete, $filterStatus), 'yearly_report.xlsx');
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

    public function showDownloadPage()
    {
        return view('report.download');
    }

    public function saveTag(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name'
        ]);
        $tag = Tags::create([
            'name' => $request->name,
        ]);
        dd("Говно");
        return response()->json(['id' => $tag->id, 'name' => $tag->name]);
    }
}
