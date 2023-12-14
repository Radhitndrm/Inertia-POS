<?php

namespace App\Http\Controllers\Apps;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProfitsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfitRequests\FilterProfitRequest;
use App\Models\Profit;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class ProfitController extends Controller
{
    public function index()
    {
        return Inertia::render('Apps/Profits/Index');
    }

    public function filter(FilterProfitRequest $request)
    {
        $validated = $request->validated();

        $profits = Profit::with('transaction')
            ->whereDate('created_at', '>=', $validated['start_date'])
            ->whereDate('created_at', '<=', $validated['end_date'])
            ->get();

        $total = Profit::whereDate('created_at', '>=', $validated['start_date'])
            ->whereDate('created_at', '<=', $validated['end_date'])
            ->sum('total');

        return Inertia::render('Apps/Profits/Index', [
            'profits'   => $profits,
            'total'     => (int) $total
        ]);
    }

    public function export(Request $request)
    {
        return Excel::download(new ProfitsExport($request->start_date, $request->end_date), 'profits : ' . $request->start_date . ' — ' . $request->end_date . '.xlsx');
    }

    public function pdf(Request $request)
    {
        //get data proftis by range date
        $profits = Profit::with('transaction')->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->get();

        //get total profit by range date
        $total = Profit::whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->sum('total');

        //load view PDF with data
        $pdf = PDF::loadView('exports.profits', compact('profits', 'total'));

        //download PDF
        return $pdf->download('profits : ' . $request->start_date . ' — ' . $request->end_date . '.pdf');
    }
}
