<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('updated_at', 'desc')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images', 'attributes']);
        return view('admin.products.show', compact('product'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $attributes = Attribute::all();

        return view('admin.products.create', compact('categories', 'attributes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'barcode' => 'nullable|string|max:255|unique:products,barcode',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:99',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'admin_note' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
            'video' => 'nullable|mimes:mp4,mov,ogg,qt|max:20480',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name) . '-' . uniqid();
        $data['is_active'] = $request->has('is_active');
        // Ensure discount_percent is at least 0 if empty
        $data['discount_percent'] = $request->discount_percent ?? 0;

        // Handle Main Image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Handle Video
        if ($request->hasFile('video')) {
            $data['video_url'] = $request->file('video')->store('products/videos', 'public');
        }

        $product = Product::create($data);

        // Handle Gallery
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('products/gallery', 'public');
                $product->images()->create(['image' => $path]);
            }
        }

        // Handle Attributes
        if ($request->has('attributes')) {
            $attributeData = [];

            foreach ($request->input('attributes') as $attributeId => $value) {
                if (!empty($value)) {
                    $attributeData[$attributeId] = ['value' => $value];
                }
            }

            $product->attributes()->sync($attributeData);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $attributes = Attribute::all();

        return view('admin.products.edit', compact('product', 'categories', 'attributes'));
    }


    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:99',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'video' => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
        ]);

        // 1. Basic Data Update
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $data['discount_percent'] = $request->discount_percent ?? 0;

        // 2. Handle Main Image Replacement
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // 3. Handle Video Replacement
        if ($request->hasFile('video')) {
            if ($product->video_url) {
                Storage::disk('public')->delete($product->video_url);
            }
            $data['video_url'] = $request->file('video')->store('products/videos', 'public');
        }

        $product->update($data);

        // 4. Handle Additional Gallery Images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('products/gallery', 'public');
                $product->images()->create(['image' => $path]);
            }
        }

        // 5. Sync Attributes
        if ($request->has('attributes')) {
            $formattedAttributes = [];
            foreach ($request->input('attributes') as $attributeId => $value) {
                if ($value !== null && $value !== '') {
                    $formattedAttributes[$attributeId] = ['value' => $value];
                }
            }
            $product->attributes()->sync($formattedAttributes);
        } else {
            $product->attributes()->detach();
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Haryt üstünlikli täzelendi!');
    }

    public function destroy(Product $product)
    {
        // Delete Files
        if ($product->image) Storage::disk('public')->delete($product->image);
        if ($product->video_url) Storage::disk('public')->delete($product->video_url);

        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->image);
        }

        $product->delete();

        return back()->with('success', 'Product deleted successfully.');
    }
}
