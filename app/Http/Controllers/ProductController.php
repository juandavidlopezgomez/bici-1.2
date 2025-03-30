<?php
// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'supplier'])->get();
        
        return Inertia::render('Products/Index', [
            'products' => $products
        ]);
    }

    public function create()
    {
        $categories = Category::where('active', true)->get();
        $suppliers = Supplier::where('active', true)->get();
        
        return Inertia::render('Products/Create', [
            'categories' => $categories,
            'suppliers' => $suppliers
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'barcode' => 'nullable|string|unique:products,barcode',
            'image' => 'nullable|image|max:2048',
        ]);

        // Generate barcode if not provided
        if (empty($validated['barcode'])) {
            $validated['barcode'] = $this->generateBarcode();
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image_path'] = $path;
        }

        // Generate SKU
        $validated['sku'] = $this->generateSku($validated['name'], $validated['category_id']);
        
        Product::create($validated);
        
        return redirect()->route('products.index')
            ->with('message', 'Producto creado exitosamente');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'supplier']);
        return Inertia::render('Products/Show', [
            'product' => $product
        ]);
    }

    public function edit(Product $product)
    {
        $categories = Category::where('active', true)->get();
        $suppliers = Supplier::where('active', true)->get();
        
        return Inertia::render('Products/Edit', [
            'product' => $product,
            'categories' => $categories,
            'suppliers' => $suppliers
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'barcode' => 'nullable|string|unique:products,barcode,'.$product->id,
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            
            $path = $request->file('image')->store('products', 'public');
            $validated['image_path'] = $path;
        }
        
        $product->update($validated);
        
        return redirect()->route('products.index')
            ->with('message', 'Producto actualizado exitosamente');
    }

    public function destroy(Product $product)
    {
        // Delete product image if exists
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        
        $product->delete();
        
        return redirect()->route('products.index')
            ->with('message', 'Producto eliminado exitosamente');
    }

    private function generateBarcode()
    {
        $prefix = '789'; // Common prefix for custom products
        $randomPart = str_pad(mt_rand(1, 9999999), 7, '0', STR_PAD_LEFT);
        $barcode = $prefix . $randomPart;
        
        // Calculate check digit
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += $i % 2 == 0 ? $barcode[$i] : $barcode[$i] * 3;
        }
        $checkDigit = (10 - ($sum % 10)) % 10;
        
        return $barcode . $checkDigit;
    }

    private function generateSku($productName, $categoryId)
    {
        $category = Category::find($categoryId);
        $prefix = strtoupper(substr($category->name, 0, 3));
        $namePart = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $productName), 0, 3));
        $random = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 4));
        
        return $prefix . '-' . $namePart . '-' . $random;
    }

    public function getProductByBarcode(Request $request)
    {
        $barcode = $request->input('barcode');
        $product = Product::where('barcode', $barcode)->first();
        
        if (!$product) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }
        
        return response()->json([
            'product' => $product
        ]);
    }
}