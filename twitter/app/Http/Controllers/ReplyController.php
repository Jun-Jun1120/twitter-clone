<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Http\Requests\ReplyRequest;
use App\Models\Tweet;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;

class ReplyController extends Controller
{
    /**
     * リプライを作成
     *
     * @param ReplyRequest $request
     * @param Tweet $tweet
     * @return RedirectResponse
     */
    public function store(ReplyRequest $request, Tweet $tweet): RedirectResponse
    {
        try {
            $reply = new Reply([
                'body' => $request->content,
                'user_id' => auth()->id(),
                'tweet_id' => $tweet->id,
            ]);
            $reply->save();

            return redirect()->route('tweets.show', $tweet->id)->with('message', 'リプライを投稿しました');
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * リプライを編集
     *
     * @param Reply $reply
     * @return View
     */
    public function edit(Reply $reply): View
    {
        try {
            return view('replies.edit', compact('reply'));
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * リプライを更新
     *
     * @param ReplyRequest $request
     * @param Reply $reply
     * @return RedirectResponse
     */
    public function update(ReplyRequest $request, Reply $reply): RedirectResponse
    {
        try {
            $reply->update(['body' => $request->content]);
            return redirect()->route('tweets.show', $reply->tweet_id);
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * リプライを削除
     *
     * @param Reply $reply
     * @return RedirectResponse
     */
    public function destroy(Reply $reply): RedirectResponse
    {
        try {
            $reply->delete();
            return back();
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Handle exceptions and return appropriate response
     *
     * @param Exception $e
     * @return RedirectResponse
     */
    protected function handleException(Exception $e): RedirectResponse
    {
        // ここでエラーログを記録したり、追加のエラー処理を行うことができます。
        return back()->with('message', '予期せぬエラーが発生しました');
    }
}
