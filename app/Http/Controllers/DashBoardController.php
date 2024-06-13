<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;


class DashBoardController extends Controller
{
    public function index()
    {
        return Inertia::render('Auth/Login');
    }

    public function dashboard()
    {
        $recipes = DB::table('recipes as r')
            ->select('r.*', 'u.name',  DB::raw('(SELECT COUNT(*) FROM likes WHERE recipe_id = r.id) AS likes_count') )
            ->leftJoin('users as u', 'u.id', '=', 'r.user_id')
            ->where("removed", 0)
            ->orderBy('created_at', 'DESC')->get();

        return Inertia::render('Dashboard', compact('recipes'));
    }
}
