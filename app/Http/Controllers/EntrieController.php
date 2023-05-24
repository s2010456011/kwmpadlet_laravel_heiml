<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Entrie;
use App\Models\Padlet;
use App\Models\Rating;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntrieController extends Controller
{
    public function index(): JsonResponse{
        $entries = Entrie::with(['padlet', 'user', 'comments', 'ratings'])->get();
        return response()->json($entries, 200);
    }

    public function findByID(string $id): JsonResponse{
        $entrie = Entrie::where('id', $id)->with(['padlet', 'user', 'comments', 'ratings'])->first();
        //wenn Padlet vorhanden ist, dann zurückgebene, ansonsten ein leeres Buch zurückgeben aber auch mit 200 Code
        return $entrie != null ? response()->json($entrie, 200) : response()->json(null, 200);
    }

    public function checkByID(string $id):JsonResponse{
        $entrie = Padlet::where('id', $id)->first();
        return $entrie != null ? response()->json(true, 200) : response()->json(false, 200);
    }

    public function save(Request $request): JsonResponse
    {
        //damit alles gemeinsam ausgeführt oder wieder gerollbacked wird
        DB::beginTransaction();

        try {
            //legt neuen Entry
            $entry = Entrie::create($request->all());

            //comments neu anlegen oder updaten
            if (isset($request['comments']) && is_array($request['comments'])) {
                foreach ($request['comments'] as $comment) {
                    //wenn vorhanden dann aktualisieren, ansonsten neu anlegen
                    $comment = Comment::firstOrNew([
                        'text' => $comment['text'],
                        'user_id' => $entry['user_id']
                    ]);
                    $entry->comments()->save($comment);
                }
            }

            //ratings neu anlegen oder updaten
            if (isset($request['ratings']) && is_array($request['ratings'])) {
                foreach ($request['ratings'] as $ratings) {
                    //wenn vorhanden dann aktualisieren, ansonsten neu anlegen
                    $ratings = Rating::firstOrNew([
                        'number' => $ratings['number'],
                        'user_id' => $entry['user_id']
                    ]);
                    $entry->ratings()->save($ratings);
                }
            }

            DB::commit();
            return response()->json($entry, 200);
        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json("Entry speichern hat fehlgeschlagen: " . $e->getMessage(), 420);
        }
    }

    //Paldet Updaten
    public function update(Request $request, int $id): JsonResponse
    {
        //damit alles gemeinsam ausgeführt oder wieder gerollbacked wird
        DB::beginTransaction();
        try {
            $entry = Entrie::with(['padlet', 'user', 'comments', 'ratings'])
                ->where('id', $id)->first();

            if ($entry != null) {
                $entry->update($request->all());
            }

            //delete all old
            $entry->comments()->delete();
            //comments neu anlegen oder updaten
            if (isset($request['comments']) && is_array($request['comments'])) {
                foreach ($request['comments'] as $comments) {
                    //comments neu anlegen oder updaten
                    //ohne ::firstOrNew sonst werden gleiche Werte zusammengefasst
                    $comment = new Comment();
                    $comment->text = $comments['text'];
                    $comment->user_id = $entry['user_id'];
                    $entry->comments()->save($comment);
                        }
                    }


            //delete all old
            $entry->ratings()->delete();
            //ratings neu anlegen oder updaten
            //ohne ::firstOrNew sonst werden gleiche Werte zusammengefasst
            if (isset($request['ratings']) && is_array($request['ratings'])) {
                foreach ($request['ratings'] as $ratings) {
                    $rating = new Rating();
                    $rating->number = $ratings['number'];
                    $rating->user_id = $entry['user_id'];
                    $entry->ratings()->save($rating);
                }
            }

            DB::commit();
            $entry = Entrie::with(['padlet', 'user', 'comments', 'ratings'])
                ->where('id', $id)->first();
            // return a vaild http response
            return response()->json($entry, 201);

        } catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("Update von Eintrag fehlgeschlagen: " . $e->getMessage(), 420);
        }
    }



    public function delete(int $id) : JsonResponse {
        $entry = Entrie::where('id', $id)->first();
        if ($entry != null) {
            $entry->delete();
            return response()->json('Eintrag (' . $id . ') erfolgreich gelöscht', 200);
        }
        else
            return response()->json('Eintrag kann nicht gelöscht werden. Existiert nicht.', 422);
    }
}
