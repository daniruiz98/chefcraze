<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class LikesController extends Controller
{
    public function toggleLike(Request $request)
    {
        $user_id = $request->input("user_id");
        $recipe_id = $request->input("recipe_id");
        $user_recipe = $request->input("user_recipe");
        $like = $request->input("like");

        $name_notifica = DB::table('users')
            ->select('name')
            ->where("id", $user_id)
            ->first();
        $name_id = DB::table('users')
            ->select('name')
            ->where("id", $user_recipe)
            ->first();

        if($like){
            DB::table("likes")->insert([
                "user_id" => $user_id,
                "recipe_id" => $recipe_id
            ]);

            DB::table("notifications")->insert([
                "user_id" => $user_recipe,
                "user_name" => $name_id->name,
                "notifica_id" => $user_id,
                "notifica_name" => $name_notifica->name,
                "type" => 'like',
                "recipe_id" => $recipe_id,
                "created_at" => Carbon::now()
            ]);
        }else{
            DB::table('likes')
                ->where('user_id', $user_id)
                ->where('recipe_id', $recipe_id)
                ->delete();
        }



    }
}
