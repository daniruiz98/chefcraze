<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeIngredient extends Model
{
    use HasFactory;
    protected $table = "recipes_ingredients";
    protected $fillable = [
        "ingredient_id",
        "recipe_id",
        "quantity",
        "unit"
    ];

}
