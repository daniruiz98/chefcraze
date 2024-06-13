<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class FriendsController extends Controller
{




    public function getFriends()
    {
        // Obtener el ID del usuario autenticado
        $userId = Auth::id();

        // Consulta para obtener los amigos del usuario autenticado
        $friends = DB::table('users')
            ->join('followers as f1', function ($join) use ($userId) {
                $join->on('users.id', '=', 'f1.following_id')
                    ->where('f1.follower_id', '=', $userId);
            })
            ->join('followers as f2', function ($join) use ($userId) {
                $join->on('users.id', '=', 'f2.follower_id')
                    ->where('f2.following_id', '=', $userId);
            })
            ->select('users.id', 'users.name', 'users.profile_photo_path')
            ->get();

        // Renderizar la vista con la lista de amigos
        return Inertia::render('Friends/Friends', compact('friends'));
    }
}
