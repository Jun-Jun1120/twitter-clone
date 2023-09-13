@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ $tweet->user ? $tweet->user->name : '削除されたユーザー' }}のツイート
                    </div>
                    <div class="card-body">
                        {{ $tweet->content }}
                    </div>
                    <div class="card-footer">
                        投稿日: {{ $tweet->created_at->format('Y-m-d H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- いいねボタンといいねの数 -->
    <div class="like-container">
        <i class="far fa-heart like-button" data-tweet-id="{{ $tweet->id }}"></i>
        <span class="like-count">{{ $tweet->likedByUsers->count() }}</span>
    </div>

    <!-- Font AwesomeのCDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- JavaScriptの読み込み -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/js/tweet-favorite.js"></script>

@endsection
