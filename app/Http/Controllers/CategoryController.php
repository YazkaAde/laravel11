<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('category', [
            'title' => 'Categories',
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:categories,name',
            'color' => 'required|max:255',
        ], [
            'name.required' => 'Nama category wajib diisi',
            'name.max' => 'nama category tidak boleh lebih dari 255 karakter',
            'name.unique' => 'Nama category sudah ada, gunakan nama yang berbeda',
            'color.required' => 'Warna wajib diisi',
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'color' => $validated['color'],
        ]);

        return redirect('/category')->with('success', 'Category created successfully!');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|max:255|unique:categories,name,'.$category->id,
            'color' => 'required|max:255',
        ], [
            'name.required' => 'Category name is required',
            'name.max' => 'Category name cannot exceed 255 characters',
            'name.unique' => 'Category name already exists',
            'color.required' => 'Color is required',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'color' => $validated['color'],
        ]);

        return redirect('/category')->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
{
    $category = Category::findOrFail($id);
    
    if ($category->posts()->exists()) {
        return redirect('/category')->with('error', 'Cannot delete category because it has associated posts.');
    }
    
    $category->delete();
    
    return redirect('/category')->with('success', 'Category deleted successfully!');
}
}