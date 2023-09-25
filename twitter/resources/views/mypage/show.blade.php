@extends('layouts.app')

@section('content')

<h1 class="mb-4 title-decorated display-7">マイページ</h1>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<!-- ユーザー情報の表示 -->
<div class="card mb-4">
    <div class="card-header">
        プロフィール情報
    </div>
    <div class="card-body">
        <p><strong>名前:</strong> {{ $authUserMeta->name }}</p>
        <p><strong>メールアドレス:</strong> {{ $authUserMeta->email }}</p>
        <p><strong>アカウント制作日:</strong> {{ $authUserMeta->created_at }}</p>
        <p>
            <strong>
                <a href="{{ route('mypage.followers') }}">
                    フォロワー: {{ $authUserMeta->followers->count() }}
                </a>
            </strong>
            <span class="mx-4"></span>
            <strong>
                <a href="{{ route('mypage.following') }}">
                    フォロー: {{ $authUserMeta->following->count() }}
                </a>
            </strong>
        </p>
    </div>
</div>

<div class="d-flex align-items-center">
    <a href="{{ route('mypage.edit') }}" class="btn btn-primary btn-custom mb-3">ユーザー情報を編集</a>

    <form action="{{ route('mypage.destroy') }}" method="post" onsubmit="
    return confirm('⚠️本当にアカウントを削除してもよろしいですか？⚠️');">
        @csrf
        @method('DELETE')

        <button type="submit" class="btn btn-danger btn-custom mb-3">アカウントを削除する</button>
    </form>

    <a href="{{ route('tweets.index') }}" class="btn btn-success btn-custom mb-3">ツイート一覧</a>

    <a href="{{ route('mypage.index') }}" class="btn btn-secondary btn-custom mb-3">ユーザー 一覧</a>

    <a href="{{ route('mypage.liked') }}" class="btn btn-info btn-custom mb-3">いいねしたツイート</a>

</div>

@endsection
