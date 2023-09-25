<?php

namespace App\Models;

use App\Models\Tweet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = ['name','email','password',];

    protected $hidden = ['password','remember_token',];

    protected $casts = ['email_verified_at' => 'datetime', ];

    protected $dates = ['deleted_at'];

    /**
    * ユーザーのプロフィールを更新
    *
    * @param array $userData
    * @return void
    */
    public function updateUserProfile(array $userData): void 
    {
        if (empty($userData['email'])) {
            unset($userData['email']);
        }

        $this->update($userData);
    }

    /**
     *  ユーザーアカウントを削除
     *
     *  @return void
     */
    public function deleteByUserId(): void
    {
        $this->delete();
    }

    /**
     *  ユーザーがフォローしているユーザーの一覧を取得
     *  @return BelongsToMany
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'followed_id');
    }

    /**
     *  ユーザーをフォローしている他のユーザーの一覧を取得
     *  @return BelongsToMany
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'user_id');
    }

    /**
     *  ユーザーをフォローする
     *  @param int $userId
     *  @return void
     */
    public function follow(int $userId): void
    {
        $this->following()->attach($userId);
    }

    /**
     * ユーザーのフォローを解除する
     *
     * @param int $userId
     * @return void
     */
    public function unfollow(int $userId): void
    {
        $this->following()->detach($userId);
    }

    /**
     * UserテーブルのidとLikeテーブルのuser_id紐付け
     *
     * @return BelongsToMany
     */
    public function likedTweets(): BelongsToMany
    {
        return $this->belongsToMany(Tweet::class, 'likes', 'user_id', 'post_id');
    }

    /**
     * ユーザーが「いいね」したツイートを取得
     *
     * @return Collection
     */
    public function fetchLikedTweets(): Collection
    {
        return $this->likedTweets()->with('user')->get();
    }
}
