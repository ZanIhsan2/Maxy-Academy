<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoriesController extends Controller
{
    public function index(): View
    {
        return view('categories.index');
    }

    public function data(Request $request): JsonResponse
    {
        $query = Category::query();
        $total = (clone $query)->count();
        $search = trim((string) $request->input('search.value', ''));

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $filtered = (clone $query)->count();
        $orderColumn = (int) $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
        $columns = ['name', 'description', 'created_at'];
        $query->orderBy($columns[$orderColumn] ?? 'created_at', $orderDirection);

        $length = (int) $request->input('length', 10);
        $start = max((int) $request->input('start', 0), 0);
        $categories = $query->skip($start)->take($length > 0 ? $length : $filtered)->get();

        return response()->json([
            'draw' => (int) $request->input('draw', 0),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $categories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Category::create($this->validatedData($request));

        return to_route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $category->update($this->validatedData($request, $category));

        return to_route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return to_route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Category $category = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . ($category?->id ?? 'NULL')],
            'description' => ['nullable', 'string'],
        ]);
    }
}
