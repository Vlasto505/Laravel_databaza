<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $categories = DB::table('categories')->get();
        return response()->json(['categories' => $categories]);
    }
    public function store(Request $request) {
        DB::table('categories')->insert([
            'name' => $request->name,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return response()->json(['message' => 'Kategória vytvorená.'], 201);
    }
    public function pinnedNotes() {
        $notes = DB::table('notes')
            ->where('is_pinned', true)
            ->whereNull('deleted_at')
            ->get();
        return response()->json(['pinned_notes' => $notes]);
    }
}
