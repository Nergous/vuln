<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Document;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $sort = $request->input('sort', 'date');
        $order = $request->input('order', 'desc');
        $filterYear = $request->input('filter_year');
        $filterMonth = $request->input('filter_month');
        $filterStatus = $request->input('filter_status');

        $query = Document::with('delayedDocument', 'tags')->orderBy($sort, $order);

        if ($filterYear) {
            if ($filterMonth && $filterMonth != 'all') {
                $query->whereYear('date', $filterYear)->whereMonth('date', $filterMonth);
            } else {
                $query->whereYear('date', $filterYear);
            }
        }

        if ($filterStatus) {
            $query->where('status', $filterStatus);
        }

        $documents = $query->get();
        $documents->each(function ($document) {
            if ($document->delayedDocument) {
                $document->delayedReason = $document->delayedDocument->reason;
            }
        });

        // Получаем уникальные года из базы данных
        $uniqueYears = Document::selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('home', compact('user', 'documents', 'uniqueYears'));
    }
}
