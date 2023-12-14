<?php

namespace App\Http\Controllers\Apps;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\SalesRequests\FilterSalesRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesController extends Controller
{
    public function index()
    {
        return Inertia::render('Apps/Sales/Index');
    }

    public function filter(FilterSalesRequest $request)
    {
        $validated = $request->validated();



        $sales = Transaction::with('cashier', 'customer')
            ->where('status', 'Paid')
            ->whereDate('created_at', '>=', $validated['start_date'])
            ->whereDate('created_at', '<=', $validated['end_date'])
            ->get();


        $total = Transaction::where('status', 'Paid')
            ->whereDate('created_at', '>=', $validated['start_date'])
            ->whereDate('created_at', '<=', $validated['end_date'])
            ->sum('grand_total');

        return Inertia::render('Apps/Sales/Index', [
            'sales' => $sales,
            'total' => (int) $total
        ]);
    }

    public function export(Request $request)
    {
        return Excel::download(new SalesExport($request->start_date, $request->end_date), 'sales :' . $request->start_date . '-' . $request->end_date . '.xlsx');
    }

    public function pdf(Request $request)
    {
        //get sales by range date
        $sales = Transaction::with('cashier', 'customer')->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->get();

        //get total sales by range daate
        $total = Transaction::whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->sum('grand_total');

        //load view PDF with data
        $pdf = PDF::loadView('exports.sales', compact('sales', 'total'));

        //return PDF for preview / download
        return $pdf->download('sales : ' . $request->start_date . ' — ' . $request->end_date . '.pdf');
    }
}
