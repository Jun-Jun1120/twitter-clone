<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class Tweet extends Model
{
    const TWEETS_PER_PAGE = 20;

    use HasFactory;

    protected $fillable = ['content', 'user_id'];

    /**
     * ユーザーとのリレーションシップを定義
     *
     * @return BelongsTo
     */
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault(['name' => '削除されたユーザー',]);
    }

    /**
     * 全てのツイートを作成日時の降順で取得
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator
    {
        return $this->orderBy('created_at', 'desc')->paginate(self::TWEETS_PER_PAGE);
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
    public function findByTweetId(int $tweetId): ?Tweet
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

    /**
     * ツイートを検索
     *
     * @param string $search
     * @return LengthAwarePaginator
     */
    public function searchByContent(string $search): LengthAwarePaginator
    {
        $query = $this->query();

        // 全角スペースを半角に変換
        $spaceConversion = mb_convert_kana($search, 's');

        // 単語を半角スペースで区切り、配列にする
        $wordArraySearched = preg_split('/[\s,]+/', $spaceConversion, -1, PREG_SPLIT_NO_EMPTY);

        // 単語をループで回し、ツイートの内容と部分一致するものがあれば、$queryとして保持される
        foreach($wordArraySearched as $value)
        {
            $query->where('content', 'like', '%'.$value.'%');
        }

        // 上記で取得した$queryをページネート
        $tweets = $query->paginate(self::TWEETS_PER_PAGE)->withQueryString();

        return $tweets;
    }

    /**
     * Likeテーブルのpost_idカラムと、idを紐付け
     *
     * @return BelongsToMany
     */
    public function likedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes', 'post_id', 'user_id');
    }

}
