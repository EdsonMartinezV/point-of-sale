<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index() {
        $products = Product::all();
        return Inertia::render('Products/Index', [
            'products' => $products
        ]);
    }

    public function show($id) {
        $product = Product::findOrFail($id);
        return Inertia::render('Products/Show', [
            'product' => $product
        ]);
    }

    public function store(StoreProductRequest $request) {
        $validated = $request->validated();
        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
    }

    public function update(UpdateProductRequest $request, $id) {
        $product = Product::findOrFail($id);
        $validated = $request->validated();
        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy($id) {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
