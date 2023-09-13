<?php

namespace App\Models;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['user_id', 'post_id'];

    /**
     * いいねをする
     *
     * @param Tweet $tweet
     * @param User $user
     * @return void
     */
    public function addLike(Tweet $tweet, User $user): void
    {
        $this->create([
            'user_id' => $user->id,
            'post_id' => $tweet->id
        ]);
    }

    /**
     * いいねを外す
     *
     * @param Tweet $tweet
     * @param User $user
     * @return void
     */
    public function removeLike(Tweet $tweet, User $user): void
    {
        $this->where('user_id', $user->id)->where('post_id', $tweet->id)->delete();
    }

    /**
     * いいねしているかどうか確認
     *
     * @param Tweet $tweet
     * @param User $user
     * @return bool
     */
    public function isLiked(Tweet $tweet, User $user): bool
    {
        return $this->where('user_id', $user->id)->where('post_id', $tweet->id)->exists();
    }

}
