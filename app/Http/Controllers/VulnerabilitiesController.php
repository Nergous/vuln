<?php

namespace App\Http\Controllers;

use App\Models\RealSolution;
use Illuminate\Http\Request;
use App\Models\Vulnerability;
use App\Models\CompensatingSolution;
use App\Models\Status;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VulnerabilitiesController extends Controller
{
    public function changeVulnerability($id)
    {
        // Найди выбранную уязвимость вместе с его real_solutions и compensating_solutions
        $vulnerability = Vulnerability::findOrFail($id);
        $document = $vulnerability->documents()->first();
        $vulnerability->load('realSolutions', 'compensatingSolutions');
        $compensating = CompensatingSolution::all();
        return view('report.change_vulnerability', compact('vulnerability', 'compensating', 'document'));
    }

    public function updateVulnerability(Request $request, $id)
    {
        $vulnerability = Vulnerability::findOrFail($id);
        $document = $vulnerability->documents()->first();

        // Проверка поля not_used
        if ($request->has('not_used') && $request->not_used) {
            $vulnerability->update(['complete_status' => 'Completed']);
            $vulnerability->update(['not_used' => 1]);
            $documentId = $vulnerability->documents()->first()->id;
            $allVulnerabilitiesHaveSolution = $document->vulnerabilities->every(function ($vulnerability) {
                return $vulnerability->complete_status === 'Completed';
            });

            if ($allVulnerabilitiesHaveSolution) {
                $document->update(['status' => 'Completed']);
            } else {
                $document->update(['status' => 'In work']);
            }
            return redirect()->route('report.all_vulnerabilites', ['id' => $documentId])->with('success', 'Уязвимость / задача успешно изменена!');
        } else {
            $vulnerability->update(['not_used' => 0]);
        }

        // Валидация файлов
        $request->validate([
            'real_solutions.*.file' => 'nullable|file|max:2048', // Максимальный размер файла 2MB
        ]);

        // Обновление реальных решений
        if ($request->has('real_solutions')) {
            $existingSolutionIds = $vulnerability->realSolutions->pluck('id')->toArray();
            $submittedSolutionIds = array_keys($request->real_solutions);

            // Удаление решений, которые были удалены на клиенте
            $solutionsToDelete = array_diff($existingSolutionIds, $submittedSolutionIds);
            RealSolution::whereIn('id', $solutionsToDelete)->delete();

            foreach ($request->real_solutions as $solutionId => $solutionData) {
                if ($solutionData !== null) {
                    $solution = RealSolution::findOrFail($solutionId);
                    $solution->update(['solution' => $solutionData]);

                    // Обработка загрузки файла для реального решения
                    if ($request->hasFile("real_solutions_files.$solutionId")) {
                        $file = $request->file("real_solutions_files.$solutionId");
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $file->move(public_path('uploads'), $fileName);
                        $solution->update(['path_to_file' => 'uploads/' . $fileName]);
                    }

                    // change vulnerability's complete_status to Completed
                    $vulnerability->update(['complete_status' => 'Completed']);
                }
            }
        } else {
            $vulnerability->realSolutions()->delete();
        }

        // Добавление новых реальных решений
        if ($request->has('new_real_solutions')) {
            foreach ($request->new_real_solutions as $newSolutionId => $newSolution) {
                if (!empty($newSolution)) {
                    $solution = RealSolution::firstOrCreate(['solution' => $newSolution]);

                    // Обработка загрузки файла для нового реального решения
                    if ($request->hasFile("new_real_solutions_files.$newSolutionId")) {
                        $file = $request->file("new_real_solutions_files.$newSolutionId");
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $file->move(public_path('uploads'), $fileName);
                        $solution->update(['path_to_file' => 'uploads/' . $fileName]);
                    }

                    $vulnerability->realSolutions()->attach($solution->id);
                }
            }
        }

        // Обновление компенсирующих решений
        if ($request->has('compensating_solutions')) {
            $vulnerability->compensatingSolutions()->sync($request->compensating_solutions);
        } else {
            $vulnerability->compensatingSolutions()->detach();
        }

        // Проверка статуса уязвимости и документа
        if ($vulnerability->realSolutions()->count() === 0 && $vulnerability->compensatingSolutions()->count() === 0) {
            $vulnerability->update(['complete_status' => 'In work']);
            // find document and change its status to in work
            $document->update(['status' => 'In work']);
        } else {
            $vulnerability->update(['complete_status' => 'Completed']);
        }

        $allVulnerabilitiesHaveSolution = $document->vulnerabilities->every(function ($vulnerability) {
            return $vulnerability->complete_status === 'Completed';
        });

        if ($allVulnerabilitiesHaveSolution) {
            $document->update(['status' => 'Completed']);
        } else {
            $document->update(['status' => 'In work']);
        }

        $documentId = $vulnerability->documents()->first()->id;

        return redirect()->route('report.all_vulnerabilites', ['id' => $documentId])->with('success', 'Уязвимость / задача успешно изменена!');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        return response()->json(['url' => asset('images/' . $imageName)]);
    }

    public function saveCompensatingSolution(Request $request)
    {
        $request->validate([
            'measure' => 'required|string|max:255',
        ]);

        $solution = CompensatingSolution::create([
            'measure' => $request->measure,
        ]);

        return response()->json(['id' => $solution->id, 'measure' => $solution->measure]);
    }

    public function addNewStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|string|max:255|unique:statuses,name',
        ]);

        $status = Status::create(['name' => $request->input('status')]);

        return response()->json(['id' => $status->id, 'name' => $status->name]);
    }

    // download file from real_solution->path_to_file
    public function downloadFile($id)
    {
        $realSolution = RealSolution::findOrFail($id);

        // Проверяем, существует ли файл
        $filePath = public_path($realSolution->path_to_file);

        if (!file_exists($filePath)) {
            return abort(404, 'Файл не найден.');
        }

        // Отправляем файл для скачивания
        return response()->download($filePath, basename($filePath));
    }

    public function getAllVulnerabilityCodes()
    {
        // Получаем все уникальные коды уязвимостей
        $codes = Vulnerability::pluck('code')->unique()->values();

        return response()->json($codes);
    }
}
