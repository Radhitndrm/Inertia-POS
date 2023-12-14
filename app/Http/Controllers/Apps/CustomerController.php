<?php

namespace App\Http\Controllers\Apps;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequests\UpdateCustomerRequest;
use App\Http\Requests\CustomerRequests\StoreCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $customers = Customer::when(request()->q, function ($customers) {
            $customers = $customers->where('name', 'like', '%' . request()->q . '%');
        })->latest()->paginate(5);

        return Inertia('Apps/Customers/Index', [
            'customers' => $customers
        ]);
    }

    public function create()
    {
        return Inertia('Apps/Customers/Create');
    }


    public function store(StoreCustomerRequest $request)
    {
        $validated = $request->validated();

        Customer::create($validated);

        return redirect()->route('apps.customers.index');
    }

    public function edit(Customer $customer)
    {
        return Inertia::render('Apps/Customers/Edit', [
            'customer' => $customer
        ]);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $validated = $request->validated();

        $customer->update($validated);

        return redirect()->route('apps.customers.index');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        $customer->delete();

        return redirect()->route('apps.customers.index');
    }
}
