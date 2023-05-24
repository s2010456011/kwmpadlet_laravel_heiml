<?php

namespace App\Http\Controllers;

use App\Models\Entrie;
use App\Models\Padlet;
use App\Models\PadletUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::with(['padlets', 'entries', 'comments', 'ratings'])->get();
        return $users != null ? response()->json($users, 200) : response()->json(null, 200);
    }

    public function findByID(int $id): JsonResponse
    {
        $user = User::where('id', $id)->with(['padlets', 'entries', 'comments', 'ratings'])->first();
        //wenn Padlet vorhanden ist, dann zurückgebene, ansonsten ein leeres Buch zurückgeben aber auch mit 200 Code
        return $user != null ? response()->json($user, 200) : response()->json(null, 200);
    }


    public function save(Request $request): JsonResponse
    {
        //damit alles gemeinsam ausgeführt oder wieder gerollbacked wird
        DB::beginTransaction();

        try {
            //legt neues Padlet an
            $user = User::create($request->all());

            DB::commit();
            $user = User::with(['padlets', 'entries', 'comments', 'ratings'])
                ->where('id', $user['id'])->first();
            return response()->json($user, 200);
        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json("User speichern hat fehlgeschlagen: " . $e->getMessage(), 420);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        //damit alles gemeinsam ausgeführt oder wieder gerollbacked wird
        DB::beginTransaction();
        try {
            $user = User::with(['padlets', 'entries', 'comments', 'ratings'])
                ->where('id', $id)->first();

            if ($user != null) {
                $user->update($request->all());

            }
            DB::commit();
            $user = User::with(['padlets', 'entries', 'comments', 'ratings'])
                ->where('id', $id)->first();
            // return a vaild http response
            return response()->json($user, 201);
        } catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating user failed: " . $e->getMessage(), 420);
        }
    }

    public function delete(int $id) : JsonResponse {
        $user = User::where('id', $id)->first();
        if ($user != null) {
            $user->delete();
            return response()->json('User (' . $id . ') erfolgreich gelöscht', 200);
        }
        else
            return response()->json('User kann nicht gelöscht werden. Existiert nicht.', 422);
    }
}
