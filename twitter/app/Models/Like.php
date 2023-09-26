<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['user_id', 'post_id'];

/**
 * いいねをする
 *
 * @param int $tweetId
 * @param int $userId
 * @return void
 */
    public function addLike(int $tweetId, int $userId): void
    {
        $this->create([
            'user_id' => $userId,
            'post_id' => $tweetId
        ]);
    }

/**
 * いいねを外す
 *
 * @param int $tweetId
 * @param int $userId
 * @return void
 */
    public function removeLike(int $tweetId, int $userId): void
    {
        $this->where('user_id', $userId)->where('post_id', $tweetId)->delete();
    }

/**
 * いいねしているかどうか確認
 *
 * @param int $tweetId
 * @param int $userId
 * @return bool
 */
    public function isLiked(int $tweetId, int $userId): bool
    {
        return $this->where('user_id', $userId)->where('post_id', $tweetId)->exists();
    }

}
