<?php

namespace App\Http\Controllers;

use App\Models\Entrie;
use App\Models\Padlet;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PadletController extends Controller
{

    public function index(): JsonResponse
    {
        $padlets = Padlet::with(['entries', 'user', 'users'])->get();
        return $padlets != null ? response()->json($padlets, 200) : response()->json(null, 200);
    }

    public function findByID(int $id): JsonResponse
    {
        $padlet = Padlet::where('id', $id)->with(['entries', 'user', 'users'])->first();
        //wenn Padlet vorhanden ist, dann zurückgebene, ansonsten ein leeres Buch zurückgeben aber auch mit 200 Code
        return $padlet != null ? response()->json($padlet, 200) : response()->json(null, 200);
    }

    public function checkByID(int $id): JsonResponse
    {
        $padlet = Padlet::where('id', $id)->first();
        return $padlet != null ? response()->json(true, 200) : response()->json(false, 200);

    }

    public function getPublic(bool $is_public): JsonResponse
    {
        $padlet = Padlet::where('is_public', $is_public)->with(['entries', 'user', 'users'])->first();
        return $padlet != null ? response()->json($padlet, 200) : response()->json(null, 200);
    }

    //über querys auf Hauptobjekte und Unterobjekte (z.B. Admin) zugreifen
    public function findBySearchTerm(string $term): JsonResponse
    {
        $padlets = Padlet::with(['entries', 'user', 'users'])
            ->where('title', 'LIKE', '%' . $term . '%')
            ->orWhere('description', 'LIKE', '%' . $term . '%')
            ->orWhereHas('user', function ($query) use ($term) {
                $query->where('firstname', 'LIKE', '%' . $term . '%')
                    ->orWhere('lastname', 'LIKE', '%' . $term . '%');
            })
            ->orWhereHas('users', function ($query) use ($term) {
                $query->where('firstname', 'LIKE', '%' . $term . '%')
                    ->orWhere('lastname', 'LIKE', '%' . $term . '%');
            })->get();
        return response()->json($padlets, 200);
    }

    //zum Speichern von Padlet
    public function save(Request $request): JsonResponse
    {
        //damit alles gemeinsam ausgeführt oder wieder gerollbacked wird
        DB::beginTransaction();

        try {
            //legt neues Padlet an
            $padlet = Padlet::create($request->all());

            if (isset($request['entries']) && is_array($request['entries'])) {
                foreach ($request['entries'] as $entry) {
                    //wenn vorhanden dann User aktualisieren, ansonsten neu anlegen
                    $entry = Entrie::firstOrNew([
                        'padlet_id' => $padlet->id,
                        'title' => $entry['title'],
                        'text' => $entry['text'],
                        'user_id' => $entry['user_id']
                    ]);
                    $padlet->entries()->save($entry);
                }
            }

            //TODO Role ID fehlt noch
           /* if (isset($request['users']) && is_array($request['users'])) {
                foreach ($request['users'] as $user) {
                    //wenn vorhanden dann User aktualisieren, ansonsten neu anlegen
                    $user = User::firstOrNew([
                        'firstname' => $user['firstname'],
                        'lastname' => $user['lastname'],
                        'image' => $user['image'],
                        'email' => $user['email']
                    ]);
                    $padlet->users()->save($user);
                }
            }*/
            DB::commit();
            return response()->json($padlet, 200);
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
            $padlet = Padlet::with(['entries', 'user', 'users'])
                ->where('id', $id)->first();

            if ($padlet != null) {
                $padlet->update($request->all());

                //delete all old
                $padlet->entries()->delete();
                // save entries
                if (isset($request['entries']) && is_array($request['entries'])) {
                    foreach ($request['entries'] as $entry) {
                        //wenn vorhanden dann User aktualisieren, ansonsten neu anlegen
                        $entry = Entrie::firstOrNew([
                            'padlet_id' => $padlet->id,
                            'title' => $entry['title'],
                            'text' => $entry['text'],
                            'user_id' => $entry['user_id']
                        ]);
                        $padlet->entries()->save($entry);
                    }
                }
            }
            DB::commit();
            $padlet = Padlet::with(['entries', 'user', 'users'])
                ->where('id', $id)->first();
            // return a vaild http response
            return response()->json($padlet, 201);
        } catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating padlet failed: " . $e->getMessage(), 420);
        }
    }

    public function delete(int $id) : JsonResponse {
        $padlet = Padlet::where('id', $id)->first();
        if ($padlet != null) {
            $padlet->delete();
            return response()->json('Padlet (' . $id . ') erfolgreich gelöscht', 200);
        }
        else
            return response()->json('Padlet kann nicht gelöscht werden. Existiert nicht.', 422);
    }
}

