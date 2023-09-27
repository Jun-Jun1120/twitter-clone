<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Tweet;
use App\Http\Requests\ReplyRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;

class ReplyController extends Controller
{
    /**
     * リプライを保存
     *
     * @param ReplyRequest $request
     * @param Tweet $tweet
     * @return RedirectResponse
     */
    public function store(ReplyRequest $request, Tweet $tweet): RedirectResponse
    {
        try {
            Reply::createReply($request->content, Auth::id(), $tweet->id);

            return redirect()->route('tweets.show', $tweet->id)->with('message', 'リプライを投稿しました！');
        } catch (Exception $e) {

            return $this->handleException($e);
        }
    }

    /**
     * リプライの編集画面を表示
     *
     * @param Reply $reply
     * @return View
     */
    public function edit(Reply $reply): View
    {
        try {
            if (is_null($reply)) {
                throw new \Exception('該当するツイートが見つかりませんでした。');
            }

            if (!$reply->isOwnedBy(Auth::id())) {
                throw new Exception('あなたはこのリプライのオーナーではありません。');
            }

            return view('replies.edit', compact('reply'))->with('message', 'リプライを編集します！');
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
            if (!$reply->isOwnedBy(Auth::id())) {
                throw new Exception('あなたはこのリプライのオーナーではありません。');
            }
            $reply->modifyReply($request->content);

            return redirect()->route('tweets.show', $reply->tweet_id)->with('message', 'リプライを更新しました！');
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
            if (!$reply->isOwnedBy(Auth::id())) {
                throw new Exception('あなたはこのリプライのオーナーではありません。');
            }
            $reply->removeReply();

            return back()->with('message', 'リプライを削除しました！');
        } catch (Exception $e) {

            return $this->handleException($e);
        }
    }

    /**
     * 発生した例外をハンドリング
     *
     * @param Exception $e
     * @return RedirectResponse
     */
    protected function handleException(Exception $e): RedirectResponse
    {
        return back()->with('message', '予期せぬエラーが発生しました');
    }
}
