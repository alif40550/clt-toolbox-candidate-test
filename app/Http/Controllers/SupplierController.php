<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
        $supplier->load([
            'layups' => function($query) {
                $query->withCount('layers')
                      ->withSum('layers as total_thickness', 'thickness');
            }
        ]);
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

    public function exportJson()
    {
        $suppliers = Supplier::with('layups.layers')->get();
        
        $filename = 'suppliers_export_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->streamDownload(function () use ($suppliers) {
            echo json_encode($suppliers, JSON_PRETTY_PRINT);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function importJson(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimetypes:application/json,text/plain|max:2048',
        ]);

        $file = $request->file('import_file');
        $jsonContent = file_get_contents($file->getRealPath());
        $data = json_decode($jsonContent, true);

        if (!$data || !is_array($data)) {
            return redirect()->back()->with('error', 'Format JSON tidak valid.');
        }

        foreach ($data as $supplierData) {
            if (!isset($supplierData['name'])) continue;

            $supplier = Supplier::create([
                'name' => $supplierData['name']
            ]);

            if (isset($supplierData['layups']) && is_array($supplierData['layups'])) {
                foreach ($supplierData['layups'] as $layupData) {
                    if (!isset($layupData['name'])) continue;

                    $layup = $supplier->layups()->create([
                        'name' => $layupData['name']
                    ]);

                    if (isset($layupData['layers']) && is_array($layupData['layers'])) {
                        foreach ($layupData['layers'] as $layerData) {
                            $layup->layers()->create([
                                'layer_order'   => $layerData['layer_order'] ?? 1,
                                'thickness'     => $layerData['thickness'] ?? 0,
                                'width'         => $layerData['width'] ?? 0,
                                'angle'         => $layerData['angle'] ?? 0,
                                'grade'         => $layerData['grade'] ?? 'C24',
                                'last_modified' => Carbon::now(),
                            ]);
                        }
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Data Supplier berhasil di-import.');
    }
}
