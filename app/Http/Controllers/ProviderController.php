<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provider;
use App\Http\Requests\StoreProviderRequest;
use App\Http\Requests\UpdateProviderRequest;
use Inertia\Inertia;

class ProviderController extends Controller
{
    public function index() {
        $providers = Provider::all();
        return Inertia::render('Providers/Index', [
            'providers' => $providers
        ]);
    }

    public function show($id) {
        $provider = Provider::findOrFail($id);
        return Inertia::render('Providers/Show', [
            'provider' => $provider
        ]);
    }

    public function store(StoreProviderRequest $request) {
        $validated = $request->validated();
        $provider = Provider::create($validated);
        return redirect()->route('providers.index')->with('success', 'Proveedor creado exitosamente.');
    }

    public function update(UpdateProviderRequest $request, $id) {
        $validated = $request->validated();
        $provider = Provider::findOrFail($id);
        $provider->update($validated);
        return redirect()->route('providers.index')->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy($id) {
        $provider = Provider::findOrFail($id);
        $provider->delete();
        return redirect()->route('providers.index')->with('success', 'Proveedor eliminado exitosamente.');
    }
}
