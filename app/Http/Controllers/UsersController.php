<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los usuarios
        $users = DB::table('users')
            ->select("id", "name", "profile_photo_path")
            ->orderBy('name')
            ->get();
        // Pasar los usuarios a la vista
        return Inertia::render('Search/Search', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        $user_recipes = DB::table('recipes as r')
            ->select('r.*', 'u.name',  DB::raw('(SELECT COUNT(*) FROM likes WHERE recipe_id = r.id) AS likes_count') )
            ->leftJoin('users as u', 'u.id', '=', 'r.user_id')
            ->where("user_id", $id)
            ->where("removed", 0)
            ->orderBy('created_at', 'DESC')
            ->get();

        $user_total_recipes = DB::table('recipes')
            ->where('user_id', $id)
            ->where('removed', 0)
            ->count();

        $follower_id = Auth::id();

        $isFollowing = $this->isFollowing($id);

        $user_total_following = DB::table('followers')->where("follower_id", $id)->count();
        $user_total_followers = DB::table('followers')->where("following_id", $id)->count();

        return Inertia::render('Users/User', compact('user', 'user_recipes', 'user_total_recipes', 'isFollowing', 'follower_id', 'user_total_followers', 'user_total_following'));
    }

    public function isFollowing($id)
    {
        $follower_id = Auth::id();
        $following_id = $id;

        $isFollowing = DB::table('followers')
            ->where('follower_id', $follower_id)
            ->where('following_id', $following_id)
            ->exists();

        return $isFollowing;
    }

    public function getFollowers($id)
    {
        $followers = DB::table('followers')
            ->join('users', 'followers.follower_id', '=', 'users.id')
            ->where('followers.following_id', $id)
            ->select('users.id', 'users.name', 'users.profile_photo_path')
            ->get();

        return response()->json($followers);
    }

    // Método para obtener los usuarios a los que sigue un usuario
    public function getFollowing($id)
    {
        $following = DB::table('followers')
            ->join('users', 'followers.following_id', '=', 'users.id')
            ->where('followers.follower_id', $id)
            ->select('users.id', 'users.name', 'users.profile_photo_path')
            ->get();

        return response()->json($following);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }
}
