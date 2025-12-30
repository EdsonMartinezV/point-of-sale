<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeasureUnit;
use App\Http\Requests\StoreMeasureUnitRequest;
use App\Http\Requests\UpdateMeasureUnitRequest;
use App\Http\Resources\MeasureUnitResource;
use Inertia\Inertia;

class MeasureUnitController extends Controller
{
    public function index() {
        $measureUnits = MeasureUnit::all();
        return Inertia::render('MeasureUnits/Index', [
            'measureUnits' => MeasureUnitResource::collection($measureUnits)
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
        return redirect()->route('measure-units.index')->with('success', 'Presentación creada exitosamente.');
    }

    public function update(UpdateMeasureUnitRequest $request, $id) {
        $measureUnit = MeasureUnit::findOrFail($id);
        $validated = $request->validated();
        $measureUnit->update($validated);
        return redirect()->route('measure-units.index')->with('success', 'Presentación actualizada exitosamente.');
    }

    public function destroy($id) {
        $measureUnit = MeasureUnit::findOrFail($id);
        $relatedProductsCount = $measureUnit->products()->count();
        if ($relatedProductsCount > 0) {
            return redirect()->route('measure-units.index')->with('error', 'No se puede eliminar la presentación porque tiene productos relacionados.');
        }
        $measureUnit->delete();
        return redirect()->route('measure-units.index')->with('success', 'Presentación eliminada exitosamente.');
    }
}
