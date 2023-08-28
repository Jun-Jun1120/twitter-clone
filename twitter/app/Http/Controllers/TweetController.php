<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Http\Requests\TweetRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;


class TweetController extends Controller
{
    /**
     * ツイート一覧を表示
     *
     * @return View
     */
    public function index(): View
    {
        $tweet = new Tweet();
        $tweets = $tweet->get();
        $loginUserId = Auth::id();

        return view('tweets.index', ['tweets' => $tweets,'authId' => $loginUserId]);
    }

    /**
     * ツイート作成画面を表示
     *
     * @return View
     */
    public function create(): View
    {
        return view('tweets.create');
    }

    /**
     * 新しいツイートを保存
     *
     * @param TweetRequest  $request
     * @return RedirectResponse
     */
    public function store(TweetRequest $request): RedirectResponse
    {
        $tweet = new Tweet();
        $tweet->createNewTweet($request->content, Auth::id());

        return redirect()->route('tweets.index');
    }



    /**
     * 指定したツイートを表示
     *
     * @param int $tweetId
     * @return View|RedirectResponse
     */
    public function show($tweetId): View|RedirectResponse
    {
        try {
            if (is_null($tweetId) || !is_numeric($tweetId)) {
                throw new \Exception('不正なツイートIDが提供されました。');
            }

            $tweet = new Tweet();
            $tweet = $tweet->findByTweetId($tweetId);

            if (is_null($tweet) || !($tweet instanceof Tweet)) {
                throw new \Exception('ツイートが見つかりませんでした。');
            }

            return view('tweets.show', ['tweet' => $tweet]);
        } catch (\Exception $e) {
            return redirect()->route('tweets.index')->with('message', '予期せぬエラーが発生しました');
        }
    }


    /**
     * 指定したツイートを編集する画面を表示
     * @param int $tweetId
     * @return view|RedirectResponse
     */
    public function edit($tweetId): View|RedirectResponse
    {
        try {
            $tweet = new Tweet();
            $tweet = $tweet->findByTweetId($tweetId);

            if (is_null($tweet)) {
                throw new \Exception('該当するツイートが見つかりませんでした。');
            }

            $loginUserId = Auth::id();

            if (!$tweet->isOwnedBy($loginUserId)) {
                throw new \Exception('あなたはこのツイートのオーナーではありません。');
            }

            return view('tweets.edit', ['tweet' => $tweet]);
        } catch (\Exception $e) {
            return redirect()->route('tweets.index')->with('message', '予期せぬエラーが発生しました');
        }
    }

    /**
     * 指定したツイートを更新
     *
     * @param  TweetRequest  $request
     * @param  int  $tweetId
     * @return RedirectResponse
     */
    public function update(TweetRequest $request, $tweetId): RedirectResponse
    {
        $tweet = (new Tweet())->findByTweetId($tweetId);

        if (!$tweet->isOwnedBy(Auth::id())) {
            return redirect()->route('tweets.index')->with('message', 'あなたはこのツイートのオーナーではありません。');
        }

        $tweet->update(['content' => $request->content]);
        return redirect()->route('tweets.index')->with('message', 'ツイートの更新に成功しました!');
    }

    /**
     * 指定したツイートを削除
     *
     * @param  int $tweetId
     * @return RedirectResponse
     */
    public function destroy($tweetId): RedirectResponse
    {
        try {
            $tweet = (new Tweet())->find($tweetId);

            if (is_null($tweet) || !($tweet instanceof Tweet)) {
                throw new \Exception('ツイートが見つかりませんでした。');
            }

            $loginUserId = Auth::id();

            if (!$tweet->isOwnedBy($loginUserId)) {
                throw new \Exception('あなたはこのツイートのオーナーではありません。');
            }

            $tweet->deleteByTweetId($tweetId);

            return redirect()->route('tweets.index')->with('message', 'ツイートが削除されました');
        } catch (\Exception $e) {
            return redirect()->route('tweets.index')->with('message', '予期せぬエラーが発生しました');
        }
    }

}
