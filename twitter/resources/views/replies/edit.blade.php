@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>リプライを編集</h1>
        <form action="{{ route('replies.update', $reply->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea class="form-control" name="content" id="content" rows="3">{{ $reply->body }}</textarea>
            </div>
            <br><br>
            <button type="submit" class="btn btn-primary">編集する</button>
        </form>
    </div>
@endsection
