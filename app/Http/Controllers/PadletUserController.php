<?php

namespace App\Http\Controllers;

use App\Models\Padlet;
use App\Models\PadletUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PadletUserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = PadletUser::with(['padlet', 'user', 'role'])->get();
        return $users != null ? response()->json($users, 200) : response()->json(null, 200);
    }

    public function findByID(int $id): JsonResponse
    {
        $padletUser = PadletUser::where('padlet_id', $id)->with(['padlet', 'user', 'role'])->first();
        return $padletUser != null ? response()->json($padletUser, 200) : response()->json(null, 200);
    }
}
