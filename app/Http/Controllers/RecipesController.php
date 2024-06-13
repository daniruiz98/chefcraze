<?php

namespace App\Http\Controllers;

use App\Http\Requests\Recipes\StoreRequest;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class RecipesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return Inertia::render('Dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ingredients = DB::table('ingredients')->orderBy('name', 'ASC')->get();
        return Inertia::render('Recipes/Create', compact('ingredients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->except('avatar', 'ingredients');
        if($request->file('avatar')){
            $file = $request->file('avatar');
            $routeName = $file->store('recipes', ['disk' => 'public']);
            $data['avatar'] = $routeName;
        }
        $recipe = Recipe::create($data);
        $recipeID = $recipe->id;

        foreach ($request["ingredients"] as $ingredient ) {
            RecipeIngredient::create([
                "ingredient_id" => $ingredient["id"],
                "recipe_id" => $recipeID,
                "quantity" => $ingredient["quantity"],
                "unit" => $ingredient["unit"]
            ]);
        }
        return to_route('dashboard');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $recipe = DB::table('recipes')
            ->where('id', $id)
            ->first();

        $name_author = DB::table('recipes as r')
            ->select('u.name')
            ->leftJoin('users as u', "u.id", "=", "r.user_id")
            ->where('r.id', $id)
            ->first();

        $ingredients = DB::table('recipes_ingredients as r')
            ->select('i.name', 'r.quantity', 'r.unit')
            ->leftJoin('ingredients as i', 'i.id', '=', 'r.ingredient_id')
            ->where('r.recipe_id', $id)
            ->get();

        $user_id= Auth::id();
        $recipe_id = $id;

        $isLike = DB::table('likes')
            ->where('user_id', $user_id)
            ->where('recipe_id', $recipe_id)
            ->exists();

        $likes_count = DB::table("likes as l")
            ->select(DB::raw("COUNT(l.id) as total_likes"))
            ->where("recipe_id", $id)
            ->first();

        $total_comments = DB::table("comments as c")
            ->select("c.comment", "u.id", "u.name", "c.created_at")
            ->leftJoin("users as u", "c.user_id", "=", "u.id")
            ->where("recipe_id", $id)
            ->get();

        return Inertia::render('Recipes/Recipe', compact('recipe', 'ingredients', 'isLike', 'user_id', 'likes_count', 'total_comments', 'name_author'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        DB::table('recipes')
            ->where('id', $id)
            ->update(['removed' => 1]);

        return to_route('dashboard');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
