<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class FollowController extends Controller
{

    public function toggleFollow(string $id, string $isFollowing)
    {
        $follower_id = Auth::id();
        $following_id = $id;


        $name_id = DB::table('users')
            ->select('name')
            ->where("id", $following_id)
            ->first();
        $name_notifica = DB::table('users')
            ->select('name')
            ->where("id", $follower_id)
            ->first();

        if ($isFollowing == 'true') {

            DB::table('followers')
                ->where('follower_id', $follower_id)
                ->where('following_id', $following_id)
                ->delete();
        } else {
            DB::table('followers')->insert([
                'follower_id' => $follower_id,
                'following_id' => $following_id,
            ]);

            DB::table("notifications")->insert([
                "user_id" => $following_id,
                "user_name" => $name_id->name,
                "notifica_id" => $follower_id,
                "notifica_name" => $name_notifica->name,
                "type" => "follower",
                "recipe_id" => null,
                "created_at" => Carbon::now()
            ]);
        }
        return Redirect::route('users.show', ['id' => $following_id]);
    }




}
