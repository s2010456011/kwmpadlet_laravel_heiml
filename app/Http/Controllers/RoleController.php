<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        $roles = Role::get();
        return $roles != null ? response()->json($roles, 200) : response()->json(null, 200);
    }


    public function save(Request $request): JsonResponse
    {
        //damit alles gemeinsam ausgeführt oder wieder gerollbacked wird
        DB::beginTransaction();

        try {
            //legt neues Padlet an
            $role = Role::create($request->all());

            DB::commit();
            $role = Role::with([])
                ->where('id', $role['id'])->first();
            return response()->json($role, 200);
        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json("Rolle speichern hat fehlgeschlagen: " . $e->getMessage(), 420);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        //damit alles gemeinsam ausgeführt oder wieder gerollbacked wird
        DB::beginTransaction();
        try {
            $role = Role::with([])
                ->where('id', $id)->first();

            if ($role != null) {
                $role->update($request->all());

            }
            DB::commit();
            $role = Role::with([])
                ->where('id', $id)->first();
            // return a vaild http response
            return response()->json($role, 201);
        } catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating role failed: " . $e->getMessage(), 420);
        }
    }

    public function delete(int $id) : JsonResponse {
        $role = Role::where('id', $id)->first();
        if ($role != null) {
            $role->delete();
            return response()->json('Rolle (' . $id . ') erfolgreich gelöscht', 200);
        }
        else
            return response()->json('Rolle kann nicht gelöscht werden. Existiert nicht.', 422);
    }
}
