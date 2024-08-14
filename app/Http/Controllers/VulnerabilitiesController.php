<?php

namespace App\Http\Controllers;

use App\Models\RealSolution;
use Illuminate\Http\Request;
use App\Models\Vulnerability;
use App\Models\CompensatingSolution;

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
        if ($request->has('real_solutions')) {
            foreach ($request->real_solutions as $solutionId => $solutionData) {
                if ($solutionData !== null) {
                    $solution = RealSolution::findOrFail($solutionId);
                    $solution->update(['solution' => $solutionData]);
                    // change vulnerability's complete_status to Completed
                    $vulnerability->update(['complete_status' => 'Completed']);
                } else {
                    $vulnerability->realSolutions()->detach($solutionId);
                }
            }

        } else {
            $vulnerability->realSolutions()->detach();
        }
        if ($request->has('compensating_solutions')) {
            $vulnerability->compensatingSolutions()->sync($request->compensating_solutions);


        } else {
            $vulnerability->compensatingSolutions()->detach();
        }

        if ($request->has('new_real_solutions')) {
            foreach ($request->new_real_solutions as $newSolution) {
                if (!empty($newSolution)) {
                    $solution = RealSolution::firstOrCreate(['solution' => $newSolution]);
                    $vulnerability->realSolutions()->attach($solution->id);

                }
            }
        }
        // chechk if vulnerability dont have solution change complete_status to In work

        if ($vulnerability->realSolutions()->count() === 0 && $vulnerability->compensatingSolutions()->count() === 0) {
            $vulnerability->update(['complete_status' => 'In work']);
            // find document and change its status to in work
            $document->update(['status' => 'In work']);
        } else {
            $vulnerability->update(['complete_status' => 'Completed']);
        }

        $allVulnerabilitiesHaveSolution = $document->vulnerabilities->every(function ($vulnerability) {
            return $vulnerability->realSolutions()->count() > 0 || $vulnerability->compensatingSolutions()->count() > 0;

        });

        if ($allVulnerabilitiesHaveSolution) {
            $document->update(['status' => 'Completed']);
        } else {
            $document->update(['status' => 'In work']);
        }

        $documentId = $vulnerability->documents()->first()->id;

        return redirect()->route('report.all_vulnerabilites', ['id' => $documentId])->with('success', 'Уязвимость успешно изменена!');
    }
}
