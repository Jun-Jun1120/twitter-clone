@extends('layouts.app')

@section('content')
    <!-- エラー表示 -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- いいねした投稿一覧表示 -->
    <div class="container">
        <div class="row justify-content-center">
            @foreach($likedTweets as $tweet)
                <div class="col-md-8 mb-4">
                    <div class="card">
                        <!-- 投稿のヘッダー（ユーザー名と投稿日時） -->
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>
                                {{ $tweet->user ? $tweet->user->name : '削除されたユーザー' }}の投稿
                            </span>
                            <small>
                                {{ $tweet->created_at->format('Y-m-d H:i') }}
                            </small>
                        </div>

                        <!-- 投稿本文 -->
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

    </div>

    <!-- スタイルとスクリプトの読み込み -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/js/tweet-favorite.js"></script>
@endsection
