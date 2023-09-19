@extends('layouts.app')

@section('content')

    <!-- ユーザー検索エラー表示 -->
    @if ($errors->has('search'))
        <div class="alert alert-danger">
            {{ $errors->first('search') }}
        </div>
    @endif

    <!-- ユーザー検索フォーム -->
    <form method="GET" action="{{ route('tweets.index') }}">
        <input type="search" placeholder="検索するキーワード" name="search" value="@if (isset($search)) {{ $search }} @endif">
        <div>
            <button type="submit">検索</button>
        </div>
    </form>

    @if(!request()->has('search'))

        <!-- フォーム送信後のメッセージ表示 -->
        @if(session('message'))
            <div class="alert {{ session('error') ? 'alert-danger' : 'alert-success' }}">
                {{ session('message') }}
            </div>
        @endif

        <!-- ツイート作成フォーム -->
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
        <br>
    @endif

    <!-- ツイート一覧表示 -->
    <div class="container">
        <div class="row justify-content-center">
            @foreach($tweets as $tweet)
                <div class="col-md-8 mb-4">
                    <div class="card">
                        <!-- ツイートのヘッダー（ユーザー名と投稿日時） -->
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>
                                {{ $tweet->user ? $tweet->user->name : '削除されたユーザー' }}のツイート
                            </span>
                            <small>
                                {{ $tweet->created_at->format('Y-m-d H:i') }}
                            </small>

                            <!-- 編集・削除ドロップダウンメニュー -->
                            @if (Auth::id() === $tweet->user_id)
                                <div class="tweet-dropdown">
                                    <a class="dots-leader text-dark text-decoration-none" id="dropdownMenuLink{{ $tweet->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                    </a>

                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink{{ $tweet->id }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('tweets.edit', $tweet->id) }}">編集する</a>
                                        </li>
                                        <li>
                                            <form action="{{ route('tweets.destroy', $tweet->id) }}" method="post" onsubmit="return confirm('本当に削除しますか？');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">削除する</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <!-- ツイート本文 -->
                        <div class="card-body">
                            <a href="{{ route('tweets.show', $tweet->id) }}" class="text-dark text-decoration-none">
                                {{ $tweet->content }}
                            </a>
                        </div>

                        <!-- いいねボタンとカウント -->
                        <hr>
                        <div class="like-container">
                            <i class="far fa-heart like-button" data-tweet-id="{{ $tweet->id }}"></i>
                            <span class="like-count">{{ $tweet->likedByUsers->count() }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ページネーションリンク -->
        {{ $tweets->links('pagination::bootstrap-4') }}
    </div>

    <!-- スタイルとスクリプトの読み込み -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/js/tweet-favorite.js"></script>

@endsection
