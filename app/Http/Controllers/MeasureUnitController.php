<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeasureUnit;
use App\Http\Requests\StoreMeasureUnitRequest;
use App\Http\Requests\UpdateMeasureUnitRequest;
use Inertia\Inertia;

class MeasureUnitController extends Controller
{
    public function index() {
        $measureUnits = MeasureUnit::all();
        return Inertia::render('MeasureUnits/Index', [
            'measureUnits' => $measureUnits
        ]);
    }

    public function show($id) {
        $measureUnit = MeasureUnit::findOrFail($id);
        return Inertia::render('MeasureUnits/Show', [
            'measureUnit' => $measureUnit
        ]);
    }

    public function store(StoreMeasureUnitRequest $request) {
        $validated = $request->validated();
        MeasureUnit::create($validated);
        return redirect()->route('measure-units.index')->with('success', 'Listo!');
    }

    public function update(UpdateMeasureUnitRequest $request, $id) {
        $measureUnit = MeasureUnit::findOrFail($id);
        $validated = $request->validated();
        $measureUnit->update($validated);
        return redirect()->route('measure-units.index')->with('success', 'Listo!');
    }

    public function destroy($id) {
        $measureUnit = MeasureUnit::findOrFail($id);
        $measureUnit->delete();
        return redirect()->route('measure-units.index')->with('success', 'Listo!');
    }
}
