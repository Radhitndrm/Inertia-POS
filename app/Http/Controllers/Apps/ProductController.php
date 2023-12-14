<?php

namespace App\Http\Controllers\Apps;

use Inertia\Inertia;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequests\StoreProductRequest;
use App\Http\Requests\ProductRequests\UpdateProductRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::when(request()->q, function ($products) {
            $products = $products->where('title', 'like', '%' . request()->q . '%');
        })->latest()->paginate(5);

        return Inertia::render('Apps/Products/Index', [
            'products' => $products
        ]);
    }

    public function create()
    {
        $categories = Category::all();

        return Inertia::render('Apps/Products/Create', [
            'categories' => $categories
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        $validated['image'] = $image->hashName();

        Product::create($validated);

        return redirect()->route('apps.products.index');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return Inertia::render('Apps/Products/Edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            Storage::disk('local')->delete('public/products/' . basename($product->image));

            $image = $request->file('image');
            $image->storeAs('public/products', $image->hashName());

            $product->image = $image->hashName();
        }

        $product->update([
            'barcode' => $validated['barcode'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'buy_price' => $validated['buy_price'],
            'sell_price' => $validated['sell_price'],
            'stock' => $validated['stock'],
        ]);

        return redirect()->route('apps.products.index');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        Storage::disk('local')->delete('public/products/' . basename($product->image));

        $product->delete();

        return redirect()->route('apps.products.index');
    }
}
