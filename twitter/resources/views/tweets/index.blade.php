@extends('layouts.app')

@section('content')

    <!-- ユーザー検索フォーム -->
    @if ($errors->has('search'))
        <div class="alert alert-danger">
            {{ $errors->first('search') }}
        </div>
    @endif

    <form method="GET" action="{{ route('tweets.index') }}">
        <input type="search" placeholder="検索するキーワード" name="search" value="@if (isset($search)) {{ $search }} @endif">
        <div>
            <button type="submit">検索</button>
        </div>
    </form>

    @if(!request()->has('search'))
        <!-- 作成フォーム -->
        @if(session('message'))
            <div class="alert {{ session('error') ? 'alert-danger' : 'alert-success' }}">
                {{ session('message') }}
            </div>
        @endif

        <div class="tweet-form-container">
            <div class="tweet-form-box">
                @error('content')
                    <div style="color: red; font-weight: bold;">{{ $message }}</div>
                @enderror

                <form action="{{ route('tweets.store') }}" method="post">
                    @csrf
                    <textarea name="content" class="tweet-textarea font-weight-bold"></textarea>
                    <button type="submit" class="tweet-submit-button">投稿する</button>
                </form>
            </div>
        </div>
    @endif

    <br><br>

    <!-- 一覧表示 -->
    <ul style="list-style-type: none;">
        @foreach($tweets as $tweet)
            <li style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
                <!-- ツイート内容をクリックすると詳細ページに移動するリンク-->
                <a href="{{ route('tweets.show', $tweet->id) }}" class="tweet-text text-dark text-decoration-none">
                    {{ $tweet->content }}
                </a>

                <!-- いいねボタンといいねの数 -->
                <div class="like-container">
                    <i class="far fa-heart like-button" data-tweet-id="{{ $tweet->id }}"></i>
                    <span class="like-count">{{ $tweet->likedByUsers->count() }}</span>
                </div>

                @if($authId === $tweet->user_id && auth()->check() && is_null(auth()->user()->deleted_at))
                    <a href="{{ route('tweets.edit', $tweet->id) }}">ツイートを編集する</a>

                    <!-- 削除フォーム -->
                    <form action="{{ route('tweets.destroy', $tweet->id) }}" method="post" onsubmit="return confirm('本当に削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="color: red;">🗑️ 削除</button>
                    </form>
                @endif
            </li>
        @endforeach
        {{ $tweets->links('pagination::bootstrap-4') }}
    </ul>

    <!-- Font AwesomeのCDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- JavaScriptの読み込み -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/js/tweet-favorite.js"></script>

@endsection
