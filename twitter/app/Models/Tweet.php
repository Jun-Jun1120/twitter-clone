<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'user_id'];

    /**
     * ユーザーとのリレーションシップを定義
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 全てのツイートを作成日時の降順で取得
     *
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->orderBy('created_at', 'desc')->get();
    }

    /**
     * 新しいツイートを作成
     *
     * @param string $content
     */
    public function createNewTweet(string $content): void
    {
        $this->content = $content;
        $this->user_id = Auth::id();
        $this->save();
    }

    /**
     * ツイートを更新
     *
     * @param string $content
     * @return boolean
     */
    public function updateTweet(string $content): bool
    {
        $this->content = $content;
        return $this->save();
    }

    /**
     * ツイートIDによってツイートを取得
     *
     * @param int $tweetId
     * @return Tweet
     */
    public function findByTweetId(int $tweetId): Tweet
    {
        return $this->find($tweetId);
    }

    /**
     * ツイートが指定したユーザーによって所有されているかを確認
     *
     * @param int $userId
     * @return boolean
     */
    public function isOwnedBy(int $userId): bool
    {
        return $this->user_id === $userId;
    }

    /**
     * ユーザーIDに基づいてツイートを削除
     *
     * @param int $tweetId
     * @return void
     */
    public function deleteByTweetId(int $tweetId): void
    {
        $tweet = $this->find($tweetId);
        $tweet->delete();
    }
}
