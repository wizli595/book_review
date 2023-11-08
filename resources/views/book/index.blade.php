@extends('layout.app')
@section('content')
    <h1 class="mb-10 text-3xl font-bold">Books</h1>
    <form method="GET" action="{{ route('book.index') }}" class="mb-4 flex items-center space-x-2">
        <input type="text" name="title" placeholder="Search by title" value="{{ request('title') }}" class="input h-10">
        <input type="hidden" name="filter" value="{{ request('filter') }}">
        <button type="submit" class="btn h-10">Search</button>
        <a href="{{ route('book.index') }}" class="btn h-10">clear</a>
    </form>
    @php
        $filters = [
            '' => 'latest',
            'popular_last_month' => 'popular last month',
            'popular_last_6months' => 'popular last 6 months',
            'highest_rated_last_month' => 'highest rated last month',
            'highest_rated_last_6months' => 'highest rated last 6 months',
        ];
    @endphp
    <div class="filter-container mb-4 flex">
        @foreach ($filters as $key => $val)
            <a href="{{ route('book.index', [...request()->query(), 'filter' => $key]) }}"
                class="{{ request('filter') === $key || (request('filter') === null && $key === '') ? 'filter-item-active' : 'filter-item' }}">{{ $val }}</a>
        @endforeach
    </div>

    <ul>
        @forelse ($books as $bk)
            <li class=" mb-4">
                <div class="book-item">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="w-full flex-grow sm:w-auto">
                            <a href={{ route('book.show', $bk) }}>{{ $bk->title }}</a>
                            <span class="book-author">{{ $bk->author }}</span>
                        </div>
                        <div>
                            <div class="book-rating">
                                <x-star-rating :rating="$bk->reviews_avg_rating" />
                            </div>
                            <div class="book-review-count">
                                out of {{ $bk->reviews_count ?? '5' }} {{ Str::plural('review', $bk->reviews_count) }}
                            </div>
                        </div>
                    </div>
            </li>
        @empty
            <li class="mb-4">
                <div class="empty-book-item">
                    <p class="empty-text">No books found</p>
                    <a href="{{ route('book.index') }}">Rest criteria</a>
                </div>
            </li>
        @endforelse
    </ul>
    <div>
        {{ $books->links() }}
    </div>
@endsection
