<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\RecipesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\MessagesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
;

Route::get('/', [DashBoardController::class, 'index']);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {

    Route::get('/dashboard', [DashBoardController::class, 'dashboard'])->name('dashboard');
    Route::prefix('dashboard')->group(function (){
        Route::get('recipes/create', [RecipesController::class, 'create'])->name('recipes.create');
        Route::post('recipes', [RecipesController::class, 'store'])->name('recipes.store');
        Route::get('recipes/show/{id}', [RecipesController::class, 'show'])->name('recipes.show');
        Route::get('users/show/{id}', [UsersController::class, 'show'])->name('users.show');
        Route::get('recipes/delete/{id}', [RecipesController::class, 'edit'])->name('delete.recipe');
        Route::get('/users', [UsersController::class, 'index'])->name('users.index');
        Route::get('/myprofile/{id}', [UsersController::class, 'show'])->name('myprofile');
        Route::get('users/followers/{id}', [UsersController::class, 'getFollowers'])->name('users.followers');
        Route::get('users/following/{id}', [UsersController::class, 'getFollowing'])->name('users.following');
        Route::get("/toggleFollow/{id}/{isFollowing}", [FollowController::class, "toggleFollow"])->name('toggleFollow');
    });
    Route::get('/notifications', [NotificationsController::class, 'getNotifications'])->name('notifications');
    Route::get('/friends', [FriendsController::class, 'getFriends'])->name('friends');
    Route::get("/conversations", [MessagesController::class, 'getConversations'])->name('conversations');
    Route::prefix('conversations')->group(function (){
        Route::get('find/{user_id}', [MessagesController::class, 'toggleConversation'])->name('conversation.find');
        Route::get('{conversation}/messages', [MessagesController::class, 'index'])->name('messages.index');

    });






});


