<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
    /**
     * 「いいね」する
     *
     * @param Tweet $tweet
     * @return JsonResponse
     */
    public function like(Tweet $tweet): JsonResponse
    {
        try {
            $like = new Like();
            $like->addLike($tweet->id, Auth::id());
            $likeCount = $tweet->getLikeCount();
            return response()->json(['isLiked' => true, 'likeCount' => $likeCount]);
        } catch (\Exception $e) {
            return response()->json(['message' => '予期せぬエラーが発生しました']);
        }
    }

    /**
     * 「いいね」を取り消す
     *
     * @param Tweet $tweet
     * @return JsonResponse
     */
    public function unlike(Tweet $tweet): JsonResponse
    {
        try {
            $like = new Like();
            $like->removeLike($tweet->id, Auth::id());
            $likeCount = $tweet->getLikeCount();
            return response()->json(['isLiked' => false, 'likeCount' => $likeCount]);
        } catch (\Exception $e) {
            return response()->json(['message' => '予期せぬエラーが発生しました']);
        }
    }

    /**
     * 「いいね」の状態を返却する
     *
     * @param Tweet $tweet
     * @return JsonResponse
     */
    public function returnLikeStatus(Tweet $tweet): JsonResponse
    {
        $like = new Like();
        if ($like->isLiked($tweet->id, Auth::id())) { 
            return response()->json(['isLiked' => true]);
        }
        return response()->json(['isLiked' => false]);
    }
}
