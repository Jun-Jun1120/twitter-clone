@extends('layouts.app')

@section('content')

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

    <br><br>

    <!-- 一覧表示 -->
    <ul style="list-style-type: none;">
        @foreach($tweets as $tweet)
            <li style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
                <!-- ツイート内容をクリックすると詳細ページに移動するリンク-->
                <a href="{{ route('tweets.show', $tweet->id) }}" class="tweet-text text-dark text-decoration-none">
                    {{ $tweet->content }}
                </a>

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
    </ul>

@endsection
