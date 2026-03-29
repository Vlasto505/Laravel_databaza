<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::all();
        return response()->json(['categories' => $categories], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'min:2', 'max:64', 'unique:categories,name'],
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'], // Validácia HEX farby
        ]);

        $category = Category::create($validated);

        return response()->json([
            'message' => 'Kategória bola úspešne vytvorená.',
            'category' => $category
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'  => [
                'sometimes', 'required', 'string', 'min:2', 'max:64',
                Rule::unique('categories', 'name')->ignore($category->id)
            ],
            'color' => ['sometimes', 'required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $category->update($validated);

        return response()->json([
            'message' => 'Kategória bola úspešne upravená.',
            'category' => $category
        ], Response::HTTP_OK);
    }
}
