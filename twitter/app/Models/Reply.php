<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'user_id', 'tweet_id'];

    /**
     * リプライを作成します
     *
     * @param string $content
     * @param int $userId
     * @param int $tweetId
     * @return self
     */
    public static function createReply(string $content, int $userId, int $tweetId): self
    {
        try {
            $reply = new Reply([
                'body' => $content,
                'user_id' => $userId,
                'tweet_id' => $tweetId,
            ]);
            $reply->save();

            return $reply;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * リプライを更新します
     *
     * @param string $content
     * @return void
     */
    public function modifyReply(string $content): void
    {
        try {
            $this->update(['body' => $content]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * リプライを削除します
     *
     * @return void
     */
    public function removeReply(): void
    {
        try {
            $this->delete();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ツイートとのリレーション
     *
     * @return BelongsTo
     */
    public function tweet(): BelongsTo
    {
        return $this->belongsTo(Tweet::class);
    }

    /**
     * ユーザーとのリレーション
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * リプライが指定したユーザーによって所有されているかを確認
     *
     * @param int $userId
     * @return boolean
     */
        public function isOwnedBy(int $userId): bool
        {
            return $this->user_id === $userId;
        }

}
