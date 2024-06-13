<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $table = "recipes";
    protected $fillable = [
        "name",
        "title",
        "avatar",
        "description",
        "custom_ingredients",
        "instructions",
        "time",
        "difficulty",
        "user_id",
        "removed"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes');
    }
}
