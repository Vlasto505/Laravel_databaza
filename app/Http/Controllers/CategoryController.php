<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
// Zmena importu na Symfony Response:
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::all();
        return response()->json(['categories' => $categories], Response::HTTP_OK);
    }

    public function store(Request $request) {
        $category = Category::create([
            'name' => $request->name,
            'color' => $request->color ?? '#808080'
        ]);

        return response()->json([
            'message' => 'Kategória bola úspešne vytvorená.',
            'category' => $category
        ], Response::HTTP_CREATED);
    }
}
