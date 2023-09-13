<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
    /**
     * 「いいね」する
     *
     * @param Request $request
     * @param Tweet $tweet
     * @return JsonResponse
     */
    public function like(Request $request, Tweet $tweet): JsonResponse
    {
        try {
            $like = new Like();
            $like->addLike($tweet, $request->user());
            $likeCount = $tweet->likedByUsers->count();
            return response()->json(['isLiked' => true, 'likeCount' => $likeCount]);
        } catch (\Exception $e) {
            return response()->json(['message' => '予期せぬエラーが発生しました']);
        }
    }

    /**
     * 「いいね」を取り消す
     *
     * @param Request $request
     * @param Tweet $tweet
     * @return JsonResponse
     */
    public function unlike(Request $request, Tweet $tweet): JsonResponse
    {
        try {
            $like = new Like();
            $like->removeLike($tweet, $request->user());
            $likeCount = $tweet->likedByUsers->count();
            return response()->json(['isLiked' => false, 'likeCount' => $likeCount]);
        } catch (\Exception $e) {
            return response()->json(['message' => '予期せぬエラーが発生しました']);
        }
    }

    /**
     * 「いいね」しているかどうかをチェックする
     *
     * @param Request $request
     * @param Tweet $tweet
     * @return JsonResponse
     */
    public function isLiked(Request $request, Tweet $tweet): JsonResponse
    {
        $like = new Like();
        if ($like->isLiked($tweet, $request->user())) {
            return response()->json(['isLiked' => true]);
        }
        return response()->json(['isLiked' => false]);
    }
}
