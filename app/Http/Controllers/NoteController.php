<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Note;
use function Laravel\Prompts\select;

class NoteController extends Controller
{
    public function index() {
        $notes = Note::query()
        ->select('id', 'user_id', 'title', 'body', 'status', 'is_pinned', 'created_at')
        ->with([
            // overit ci je categories alebo category
            'user:id, first_name, last_name',
            'categories:id, id, name, color',
        ])
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->get();
        return response()->json(['notes' => $notes], Response::HTTP_OK);
    }
    public function store(Request $request) {
        //DB::table('notes')->insert([
          //  'user_id' => $request->user_id,
            //'title' => $request->title,
           // 'body' => $request->body,
           // 'created_at' => now(),
           // 'updated_at' => now(),
        //]);
        $note = Note::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'body' => $request->body,
        ]);
        return response()->json([
            'message' => 'Poznámka bola úspešne vytvorená.'
        ], Response::HTTP_CREATED);
    }
    public function show(string $id)
    {
        $note = \App\Models\Note::with([
            'user',
            'categories',
            'tasks.comments', // komentáre k úlohám
            'comments'        // komentáre k samotnej poznámke
        ])->find($id);

        if (!$note) {
            return response()->json([
                'message' => 'Poznámka sa nenašla.'
            ], \Illuminate\Http\Response::HTTP_NOT_FOUND);
        }

        // Úspešná odpoveď s dátami
        return response()->json([
            'note' => $note
        ], \Illuminate\Http\Response::HTTP_OK);
    }

    public function update(Request $request, string $id) {
        //$note = DB::table('notes')->where('id', $id)->first();
        $note = Note::find($id);
        if (!$note) {
            return response()->json([
                'message' => 'Poznámka nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }
        $note->update([
            'title' => $request->title,
            'body' => $request->body,
        ]);
        //DB::table('notes')->where('id', $id)->update([
          //  'title' => $request->title,
          // // 'body' => $request->body,
           // 'updated_at' => now(), // dôležité pre prehľadnosť zmien
        //]);

        return response()->json([
            'message' => 'Poznámka bola úspešne aktualizovaná.'
        ], Response::HTTP_OK);
    }
    public function destroy(string $id) {
        //$note = DB::table('notes')
          //  ->where('id', $id)
           // ->whereNull('deleted_at')
           // ->first();
        $note = Note::find($id);
        if (!$note) {
            return response()->json([
                'message' => 'Poznámka nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }
        $note->delete(); //soft delete
        // SOFT DELETE: Len nastavíme aktuálny čas do deleted_at
       // DB::table('notes')->where('id', $id)->update([
         //   'deleted_at' => now(),
           // 'updated_at' => now(),
        //]);

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

    public function publish(string $id) {
        $note = Note::find($id);

        if (!$note) {
            return response()->json(['message' => 'Poznámka nenájdená.'], 404);
        }

        $note->publish();

        return response()->json([
            'message' => 'Poznámka bola publikovaná.',
            'note' => $note
        ], 200);
    }
    public function search(Request $request) {
        $q = trim((string) $request->input('q', ''));
        $notes = Note::searchPublished($q);

        return response()->json(['query' => $q, 'notes' => $notes], Response::HTTP_OK);
    }
    public function pinnedNotes() {
        $notes = \App\Models\Note::where('is_pinned', true)->get();

        return response()->json(['pinned_notes' => $notes], Response::HTTP_OK);
    }
    public function archive(string $id) {
        $note = Note::findOrFail($id);
        $note->archive();
        return response()->json(['message' => 'Poznámka bola archivovaná.', 'note' => $note]);
    }

    public function togglePin(string $id) {
        $note = Note::findOrFail($id);
        $note->togglePin();
        $status = $note->is_pinned ? 'pripnutá' : 'odpnutá';
        return response()->json(['message' => "Poznámka bola {$status}.", 'note' => $note]);
    }


}
