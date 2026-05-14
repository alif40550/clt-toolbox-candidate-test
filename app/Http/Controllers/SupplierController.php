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

        return redirect()->back();
    }
    public function show(Supplier $supplier)
    {
        $supplier->load(['layups' => function($query){
            $query->withCount('layers');
        }]);
        return view('supplier.show', [
            'supplier' => $supplier,
        ]);
    }
    public function update(SupplierRequest $request, Supplier $supplier)
    {   
        $supplier->update([
            'name' => $request->validated('supplierName'),
        ]);

        return redirect()->back();
    }
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->back();
    }

}
