<?php

namespace App\Http\Controllers;

use App\Models\Entrie;
use App\Models\Padlet;
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
            //legt neues Padlet an
            $entry = Entrie::create($request->all());

            DB::commit();
            return response()->json($entry, 200);
        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json("Padlet speichern hat fehlgeschlagen: " . $e->getMessage(), 420);
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
