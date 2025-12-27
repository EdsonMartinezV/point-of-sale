<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index(Request $request) {
        $categories = Category::all();
        return Inertia::render('Categories/Index', [
            'categories' => $categories,
        ]);
    }

    /* public function show($id) {
        $category = Category::findOrFail($id);
        return Inertia::render('Categories/Show', [
            'category' => $category
        ]);
    } */

    public function store(StoreCategoryRequest $request) {
        $validated = $request->validated();
        Category::create($validated);
        return redirect()->route('categories.index')->with('success', 'Categoria creada con éxito');
    }

    public function update(UpdateCategoryRequest $request, $id) {
        $category = Category::findOrFail($id);
        $validated = $request->validated();
        $category->update($validated);
        return to_route('categories.index')->with('success', 'Categoria actualizada con éxito');
    }

    public function destroy(Request $request, $id) {
        $category = Category::findOrFail($id);
        $relatedProductsCount = $category->products()->count();
        if ($relatedProductsCount > 0) {
            return to_route('categories.index')->with('error', 'No se puede eliminar la categoría porque tiene productos relacionados');
        }
        $category->delete();
        return to_route('categories.index')->with('success', 'Categoria eliminada con éxito');
    }
}
