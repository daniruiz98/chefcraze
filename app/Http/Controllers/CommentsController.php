<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CommentsController extends Controller
{
    public function addComment(Request $request){

        $user_id = $request->input("user_id"); // el que ha comentado
        $recipe_id = $request->input("recipe_id"); // la receta en la que ha comentado
        $comment = $request->input("comment"); // el comentario
        $user_recipe = $request->input("user_recipe"); // usuario al que han comentado

        $idComment = DB::table("comments")->insertGetId([
            "user_id" => $user_id,
            "recipe_id" => $recipe_id,
            "comment" => $comment,
            "created_at" => Carbon::now()
        ]);

        $name_notifica = DB::table('users')
            ->select('name')
            ->where("id", $user_id)
            ->first();
        $name_id = DB::table('users')
            ->select('name')
            ->where("id", $user_recipe)
            ->first();

        DB::table("notifications")->insert([
            "user_id" => $user_recipe,
            "user_name" => $name_id->name,
            "notifica_id" => $user_id,
            "notifica_name" => $name_notifica->name,
            "type" => "comment",
            "recipe_id" => $recipe_id,
            "created_at" => Carbon::now()
        ]);

        $newComment = DB::table("comments as c")
            ->select("c.comment", "u.id", "u.name", "c.created_at")
            ->leftJoin("users as u", "c.user_id", "=", "u.id")
            ->where("c.id", $idComment)
            ->first();

        return response()->json([
            "comment" => $newComment
            ]);


    }
}
