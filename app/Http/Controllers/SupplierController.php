<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('layups')->get();
        return view('supplier.index', [
            'suppliers' => $suppliers,
        ]);
    }
    public function store(SupplierRequest $request)
    {
        Supplier::create([
            'name' => $request->validated('supplierName'),
        ]);

        return redirect()->route('supplier.index');
    }
    public function show()
    {
        //
    }
    public function update(SupplierRequest $request, Supplier $supplier)
    {   
        $supplier->update([
            'name' => $request->validated('supplierName'),
        ]);

        return redirect()->route('supplier.index');
    }
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('supplier.index');
    }

}
