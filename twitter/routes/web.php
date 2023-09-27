<?php

use App\Http\Controllers\LikeController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// General Routes
Route::get('/', [TweetController::class, 'index']);
Route::get('/home', [TweetController::class, 'index'])->name('home');
Auth::routes();

Route::middleware(['auth'])->group(function() {

    // ツイート機能に関するルート
    Route::group(['prefix' => 'tweets', 'as' => 'tweets.'], function() {
        Route::get('/', [TweetController::class, 'index'])->name('index');
        Route::get('/create', [TweetController::class, 'create'])->name('create');
        Route::post('/', [TweetController::class, 'store'])->name('store');
        Route::get('/{tweet}', [TweetController::class, 'show'])->name('show');
        Route::get('/{tweet}/edit', [TweetController::class, 'edit'])->name('edit');
        Route::put('/{tweet}', [TweetController::class, 'update'])->name('update');
        Route::delete('/{tweet}', [TweetController::class, 'destroy'])->name('destroy');

    // いいね機能に関するルート
        Route::post('/{tweet}/like', [LikeController::class, 'like'])->name('like');
        Route::delete('/{tweet}/unlike', [LikeController::class, 'unlike'])->name('unlike');
        Route::get('/{tweet}/returnLikeStatus', [LikeController::class, 'returnLikeStatus'])->name('returnLikeStatus');
    });

    // リプライに関するルート
    Route::group(['prefix' => 'replies', 'as' => 'replies.'], function() {
        Route::post('/{tweet}', [ReplyController::class, 'store'])->name('store');
        Route::get('/{reply}/edit', [ReplyController::class, 'edit'])->name('edit');
        Route::put('/{reply}', [ReplyController::class, 'update'])->name('update');
        Route::delete('/{reply}', [ReplyController::class, 'destroy'])->name('destroy');
    });

    // マイページに関するルート
    Route::group(['prefix' => 'mypage', 'as' => 'mypage.'], function() {
        Route::get('/edit', [UserProfileController::class, 'edit'])->name('edit');
        Route::get('/', [UserProfileController::class, 'index'])->name('index');
        Route::get('/show', [UserProfileController::class, 'show'])->name('show');
        Route::put('/', [UserProfileController::class, 'update'])->name('update');
        Route::delete('/', [UserProfileController::class, 'destroy'])->name('destroy');
        Route::get('/users', [UserProfileController::class, 'index'])->name('users.index');
        Route::get('/liked', [UserProfileController::class, 'showLikedTweets'])->name('liked');

        // フォローに関するルート
        Route::post('/follow/{userId}', [UserProfileController::class, 'follow'])->name('follow');
        Route::post('/unfollow/{userId}', [UserProfileController::class, 'unfollow'])->name('unfollow');
        Route::get('/following', [UserProfileController::class, 'showFollows'])->name('following');
        Route::get('/followers', [UserProfileController::class, 'showFollowers'])->name('followers');
    });

});

