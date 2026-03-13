<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class NoteController extends Controller
{
    public function index() {
        $notes = DB::table('notes')
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json(['notes' => $notes], Response::HTTP_OK);
    }
    public function store(Request $request) {
        DB::table('notes')->insert([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'body' => $request->body,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Poznámka bola úspešne vytvorená.'
        ], Response::HTTP_CREATED);
    }
    public function show(string $id) {
        $note = DB::table('notes')
            ->whereNull('deleted_at')
            ->where('id', $id)
            ->first();

        if (!$note) {
            return response()->json([
                'message' => 'Poznámka nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['note' => $note], Response::HTTP_OK);
    }
    public function update(Request $request, string $id) {
        // 1. Najskôr skúsime nájsť poznámku, či vôbec existuje
        $note = DB::table('notes')->where('id', $id)->first();

        if (!$note) {
            return response()->json([
                'message' => 'Poznámka nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }

        // 2. Ak existuje, vykonáme UPDATE
        DB::table('notes')->where('id', $id)->update([
            'title' => $request->title,
            'body' => $request->body,
            'updated_at' => now(), // dôležité pre prehľadnosť zmien
        ]);

        return response()->json([
            'message' => 'Poznámka bola úspešne aktualizovaná.'
        ], Response::HTTP_OK);
    }
    public function destroy(string $id) {
        // Nájdeme poznámku, ktorá ešte nebola zmazaná
        $note = DB::table('notes')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$note) {
            return response()->json([
                'message' => 'Poznámka nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }

        // SOFT DELETE: Len nastavíme aktuálny čas do deleted_at
        DB::table('notes')->where('id', $id)->update([
            'deleted_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Poznámka odstránená.'
        ], Response::HTTP_OK);
    }
    public function statsByStatus() {
        $stats = DB::table('notes')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return response()->json(['stats' => $stats]);
    }
}
