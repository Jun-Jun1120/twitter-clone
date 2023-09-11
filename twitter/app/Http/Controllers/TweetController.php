<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Http\Requests\TweetRequest;
use App\Http\Requests\SearchRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class TweetController extends Controller
{

    /**
     * ツイート一覧を表示
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function index(SearchRequest $request): View|RedirectResponse
    {
        $tweet = new Tweet();

        if ($request->has('search')) {
            $tweets = $tweet->searchByContent($request->get('search'));
        } else {
            $tweets = $tweet->getAll();
        }

        return view('tweets.index', ['tweets' => $tweets,'authId' => Auth::id()]);
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
        $tweet->createNewTweet($request->content);

        return redirect()->route('tweets.index')->with('message', 'ツイートが作成されました');;
    }

    /**
     * 指定したツイートを表示
     *
     * @param int $tweetId
     * @return View|RedirectResponse
     */
    public function show(int $tweetId): View|RedirectResponse
    {
        try {
            if (is_null($tweetId) || !is_numeric($tweetId)) {
                throw new \Exception('不正なツイートIDが提供されました。');
            }

            $tweet = new Tweet();
            $tweet = $tweet->findByTweetId($tweetId);

        if (is_null($tweet) || !($tweet instanceof Tweet)) {
            return redirect()->route('tweets.index')->with('message', 'このツイートは既に削除されています');
        }

            return view('tweets.show', ['tweet' => $tweet]);
        } catch (\Exception $e) {
            return redirect()->route('tweets.index')->with('message', '予期せぬエラーが発生しました');
        }
    }

    /**
     * 指定したツイートを編集する画面を表示
     * @param int $tweetId
     * @return View|RedirectResponse
     */
    public function edit(int $tweetId): View|RedirectResponse
    {
        try {
            $tweet = new Tweet();
            $tweet = $tweet->findByTweetId($tweetId);

            if (is_null($tweet)) {
                throw new \Exception('該当するツイートが見つかりませんでした。');
            }

            if (!$tweet->isOwnedBy(Auth::id())) {
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
    public function update(TweetRequest $request, int $tweetId): RedirectResponse
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
    public function destroy(int $tweetId): RedirectResponse
    {
        try {
            $tweet = (new Tweet())->find($tweetId);

            if (is_null($tweet) || !($tweet instanceof Tweet)) {
                throw new \Exception('ツイートが見つかりませんでした。');
            }

            if (!$tweet->isOwnedBy(Auth::id())) {
                throw new \Exception('あなたはこのツイートのオーナーではありません。');
            }

            $tweet->deleteByTweetId($tweetId);

            return redirect()->route('tweets.index')->with('message', 'ツイートが削除されました');
        } catch (\Exception $e) {
            return redirect()->route('tweets.index')->with('message', '予期せぬエラーが発生しました');
        }
    }

}
