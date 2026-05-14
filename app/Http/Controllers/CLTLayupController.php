<?php

namespace App\Http\Controllers;

use App\Http\Requests\CLTLayupRequest;
use App\Models\CLT_Layup;
use App\Models\Supplier;

class CLTLayupController extends Controller
{
    public function store(CLTLayupRequest $request, Supplier $supplier)
    {
        $supplier->layups()->create([
            'name' => $request->validated('layupName'),
        ]);
        return redirect()->back();
    }
    public function update(CLTLayupRequest $request, CLT_Layup $layup)
    {
        $layup->update([
            'name' => $request->validated('layupName'),
        ]);
        return redirect()->back();
    }
    public function destroy(CLT_Layup $layup)
    {
        $layup->delete();
        return redirect()->back();
    }
}
