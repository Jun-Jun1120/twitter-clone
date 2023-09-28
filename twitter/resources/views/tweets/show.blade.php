@extends('layouts.app')

@section('content')

    <div class="container">
        <!-- セッションメッセージの表示 -->
        @if(session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-8 mb-4">
                <div class="card">
                    <!-- ツイートのヘッダー（ユーザー名と投稿日時） -->
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>
                            {{ $tweet->user ? $tweet->user->name : '削除されたユーザー' }}さんのツイート
                        </span>
                        <small>
                            {{ $tweet->created_at->format('Y-m-d H:i') }}
                        </small>
                    </div>

                    <!-- ツイートの本文 -->
                    <div class="card-body">
                        {{ $tweet->content }}
                    </div>

                    <!-- いいねボタンとメニュー -->
                    <hr>
                    <div class="actions-container d-flex justify-content-between align-items-center">
                        <!-- いいねボタンとカウント -->
                        <div class="like-container">
                            <i class="far fa-heart like-button" data-tweet-id="{{ $tweet->id }}"></i>
                            <span class="like-count">{{ $tweet->likedByUsers->count() }}</span>
                        </div>

                        <!-- 編集・削除メニュー　-->
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
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <form method="POST" action="{{ route('replies.store', $tweet->id) }}" novalidate>
                    @csrf
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <p style="color: red;">{{ $error }}</p>
                        @endforeach
                    @endif
                    <textarea name="content" required placeholder="リプライを入力..." class="form-control" style="resize: none;"></textarea>
                    <button type="submit" class="btn btn-primary mt-2">送信</button>
                </form>
            </div>
        </div>

        <div class="row justify-content-center mt-4">
            @foreach($tweet->replies->sortByDesc('created_at') as $reply)
                <div class="col-md-8 mb-2">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>
                                {{ $reply->user ? $reply->user->name : '削除されたユーザー' }}のリプライ
                            </span>
                            <small>
                                {{ $reply->created_at->format('Y-m-d H:i') }}
                            </small>

                            @if (Auth::id() === $reply->user_id)
                                <div class="reply-dropdown">
                                    <a class="dots-leader text-dark text-decoration-none" id="dropdownMenuLink{{ $reply->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink{{ $reply->id }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('replies.edit', $reply->id) }}">編集する</a>
                                        </li>
                                        <li>
                                            <form action="{{ route('replies.destroy', $reply->id) }}" method="post" onsubmit="return confirm('本当に削除しますか？');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">削除する</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <p>{{ $reply->body }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- スタイルとスクリプトの読み込み -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/js/tweet-favorite.js"></script>

@endsection
