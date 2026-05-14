<?php

namespace App\Http\Controllers;

use App\Http\Helper\LayerHelper;
use App\Http\Requests\CLTLayerRequest;
use App\Http\Requests\CLTLayupRequest;
use App\Models\CLT_Layer;
use App\Models\CLT_Layup;
use App\Models\Supplier;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class CLTLayupController extends Controller
{
    public function store(CLTLayupRequest $request, Supplier $supplier)
    {
        $supplier->layups()->create([
            'name' => $request->validated('layupName'),
        ]);
        return redirect()->back();
    }

    public function edit(CLT_Layup $layup, LayerHelper $helper)
    {
        $layup->load(['layers' => function ($query) {
            $query->orderBy('layer_order', 'asc');
        }]);

        $layersData = $helper->map($layup);

        return view('supplier.edit', [
            'layup'          => $layup,
            'totalThickness' => $layup->layers()->sum('thickness'),
            'totalLayers'    => $layup->layers()->count(),
            'layersData'     => $layersData,
        ]);
    }

    public function update(CLTLayupRequest $request, CLT_Layup $layup)
    {
        $layup->update([
            'name' => $request->validated('layupName'),
        ]);
        return redirect()->back();
    }


    public function saveLayers(CLTLayerRequest $request, CLT_Layup $layup)
    {
        $incomingLayers = $request->validated('layers');
        $now = Carbon::now();

        $incomingIds = collect($incomingLayers)
            ->pluck('id')
            ->filter()
            ->values();

        CLT_Layer::where('layup_id', $layup->id)
            ->whereNotIn('id', $incomingIds)
            ->delete();

        foreach ($incomingLayers as $layerData) {
            $layup->layers()->updateOrCreate(
                ['id' => $layerData['id'] ?? null],
                [
                    'layer_order'   => $layerData['layer_order'],
                    'thickness'     => $layerData['thickness'],
                    'width'         => $layerData['width'],
                    'angle'         => $layerData['angle'],
                    'grade'         => $layerData['grade'],
                    'last_modified' => $now,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Layer berhasil disimpan.',
        ]);
    }

    public function destroy(CLT_Layup $layup)
    {
        $layup->delete();
        return redirect()->back();
    }

    public function exportJson(Supplier $supplier)
    {
        $layups = $supplier->layups()->with('layers')->get();
        
        $filename = 'layups_export_supplier_' . $supplier->id . '_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->streamDownload(function () use ($layups) {
            echo json_encode($layups, JSON_PRETTY_PRINT);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function importJson(Request $request, Supplier $supplier)
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

        foreach ($data as $layupData) {
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

        return redirect()->back()->with('success', 'Data Layup berhasil di-import.');
    }
}

