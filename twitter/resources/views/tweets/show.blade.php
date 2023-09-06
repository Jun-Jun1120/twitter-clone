@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ $tweet->user->name }} のツイート
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
@endsection
