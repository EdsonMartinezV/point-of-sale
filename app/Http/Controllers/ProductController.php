<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportProductsRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MeasureUnitResource;
use App\Http\Resources\ProductResource;
use App\Imports\ProductsImport;
use App\Models\Category;
use App\Models\MeasureUnit;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function importForm(Request $request) {
        return Inertia::render('Products/Import');
    }

    public function import(ImportProductsRequest $request) {
        $validated = $request->validated();
        $file = $validated['file'];

        Excel::import(new ProductsImport, $file);

        return redirect()->route('products.index')->with('success', 'Productos importados exitosamente.');
    }

    public function index() {
        $products = Product::with(['category', 'measureUnit', 'retailMeasureUnit'])->get();
        $categories = Category::all();
        $measureUnits = MeasureUnit::all();
        return Inertia::render('Products/Index', [
            'products' => ProductResource::collection($products),
            'categories' => CategoryResource::collection($categories),
            'measureUnits' => MeasureUnitResource::collection($measureUnits),
        ]);
    }

    /* public function show($id) {
        $product = Product::findOrFail($id);
        return Inertia::render('Products/Show', [
            'product' => $product
        ]);
    } */

    public function search(Request $request) {
        $q = trim((string) $request->query('q'));

        $query = Product::query();
        if ($q !== '') {
            $query = Product::where('name', 'like', "%$q%");
        }

        $products = $query->limit(50)->get();

        // If request expects JSON (AJAX), return JSON; otherwise render Inertia view
        if ($request->wantsJson() || $request->ajax()) {
            return ProductResource::collection($products);
        }

        return Inertia::render('Products/Index', [
            'products' => $products,
            'search' => $q,
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
        $relatedPurchaseItemsCount = $product->purchaseItems()->count();
        $relatedSaleItemsCount = $product->saleItems()->count();
        if ($relatedPurchaseItemsCount > 0 || $relatedSaleItemsCount > 0) {
            return redirect()->route('products.index')->with('error', 'No se puede eliminar el producto porque tiene registros relacionados de compras o ventas.');
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Producto eliminado exitosamente.');
    }


}
