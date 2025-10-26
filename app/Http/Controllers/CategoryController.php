<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Inertia\Inertia;

class CategoryController extends Controller
{
    // Display a listing of the categories
    public function index()
    {
        $categories = Category::all();
        return Inertia::render('Categories/Index', [
            'categories' => $categories
        ]);
    }

    // Display the specified category
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return Inertia::render('Categories/Show', [
            'category' => $category
        ]);
    }

    // Store a newly created category in storage
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    // Update the specified category in storage
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    // Remove the specified category from storage
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
