<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
    public function updateProfile(array $userData): void
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
    public function removeAccount():void
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

    use HasFactory;

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
     *  @param User $user
     *  @return void
     */
    public function follow(User $user): void
    {
        $this->following()->attach($user->id);
    }

    /**
     * ユーザーのフォローを解除する
     *
     * @param User $user
     * @return void
     */
    public function unfollow(User $user): void
    {
        $this->following()->detach($user->id);
    }

}
