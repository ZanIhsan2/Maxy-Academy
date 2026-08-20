<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductsController extends Controller
{
    public function index(): View
    {
        return view('products.index', [
            'categories' => \App\Models\Category::orderBy('name')->get(),
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $query = Product::query()
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name');
        $total = Product::count();
        $search = trim((string) $request->input('search.value', ''));

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('products.sku', 'like', "%{$search}%")
                    ->orWhere('products.name', 'like', "%{$search}%")
                    ->orWhere('categories.name', 'like', "%{$search}%")
                    ->orWhere('products.description', 'like', "%{$search}%");
            });
        }

        $filtered = (clone $query)->count('products.id');
        $orderColumn = (int) $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
        $columns = ['products.sku', 'products.name', 'categories.name', 'products.price', 'products.stock', 'products.description'];
        $query->orderBy($columns[$orderColumn] ?? 'products.created_at', $orderDirection);
        $length = (int) $request->input('length', 10);
        $start = max((int) $request->input('start', 0), 0);
        $products = $query->skip($start)->take($length > 0 ? $length : $filtered)->get();

        return response()->json([
            'draw' => (int) $request->input('draw', 0),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $products,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Product::create($this->validatedData($request));

        return to_route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $product->update($this->validatedData($request, $product));

        return to_route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return to_route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku,' . ($product?->id ?? 'NULL')],
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
