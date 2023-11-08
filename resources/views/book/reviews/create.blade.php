@extends('layout.app')
@section('content')
    <h1 class="mb-10 text-2xl">Add Review for : {{ $book->title }}</h1>
    <form method="POST" action="{{ route('book.reviews.store', $book) }}">
        @csrf
        <label for="review">Review</label>
        <textarea name="review" id="review" required class="input mb-1 resize-none"></textarea>
        @error('review')
            <p class="err">{{ $message }}</p>
        @enderror
        <label for="rating">Rating</label>
        <select name="rating" id="rating" class="input mb-4" required>
            <option value="">Select a Rating</option>
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
        @error('rating')
            <p class="err">{{ $message }}</p>
        @enderror
        <button type="submit" class="btn">Add Review</button>
    </form>
@endsection
