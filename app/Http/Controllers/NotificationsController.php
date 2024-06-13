<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class NotificationsController extends Controller
{

    public function getNotifications(){
        $user_id = Auth::id();

        $notifications = DB::table("notifications as n")
            ->select("n.*", "r.title")
            ->leftJoin("recipes as r", function($join) {
                $join->on("r.id", "=", "n.recipe_id");
            })
            ->where("n.user_id", $user_id)
            ->orderBy('created_at', 'DESC')
            ->get();

        return Inertia::render("Notifications/Notifications", compact("notifications"));
    }



}
