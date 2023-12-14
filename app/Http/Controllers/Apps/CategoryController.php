<?php

namespace App\Http\Controllers\Apps;

use Inertia\Inertia;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequests\StoreCategoryRequest;
use App\Http\Requests\CategoryRequests\UpdateCategoryRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function index()
    {
        $categories = Category::when(request()->q, function ($categories) {
            $categories = $categories->where('name', 'like', '%' . request()->q . '%');
        })->latest()->paginate(5);

        return Inertia::render('Apps/Categories/Index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        return Inertia::render('Apps/Categories/Create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        $image = $request->file('image');

        $image->storeAs('public/categories', $image->hashName());

        $validated['image'] = $image->hashName();

        Category::create($validated);

        return redirect()->route('apps.categories.index');
    }

    public function edit(Category $category)
    {
        return Inertia::render('Apps/Categories/Edit', [
            'category' => $category,
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            // Hapus gambar yang ada
            Storage::disk('local')->delete('public/categories/' . basename($category->image));

            // Simpan gambar yang baru
            $image = $request->file('image');
            $image->storeAs('public/categories', $image->hashName());

            // Perbarui kolom 'image' dalam model kategori
            $category->image = $image->hashName();
        }

        // Perbarui nama dan deskripsi kategori
        $category->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        return redirect()->route('apps.categories.index');
    }

    public function destroy($id)
    {
        $category  =  Category::findOrFail($id);

        Storage::disk('local')->delete('public/categories/' . basename($category->image));

        $category->delete();

        return redirect()->route('apps.categories.index');
    }
}
