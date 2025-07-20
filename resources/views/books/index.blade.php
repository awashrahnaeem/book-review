<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Book Reviews
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold mb-6">Book Reviews</h1>
            <form method="GET" action="{{ route('books.index') }}" class="mb-4 flex items-center space-x-2">
                <input type="text" name="title" value="{{ request('title') }}" class="input mb-4 h-10" placeholder="Search books by title or author"/>
                <button type="submit" class="btn mb-4">Search</button>
                <a href="{{ route('books.index') }}" class="btn mb-4 h-10">Reset</a>
            </form>

            <div class="mb-4 filter-container flex">
                @php
                    $filters = [
                        '' => 'Latest',
                        'popular_last_month' => 'Popular Last Month',
                        'popular_last_6months' => 'Popular Last 6 Months',
                        'highest_rated_last_month' => 'Highest Rated Last Month',
                        'highest_rated_last_6months' => 'Highest Rated Last 6 Months',
                    ];
                @endphp
                @foreach ($filters as $key => $label)
                    <a href="{{ route('books.index', [...request()->query(), 'filter' => $key]) }}"
                        class="{{ request('filter') === $key || (request('filter') === null && $key === '') ? 'filter-item-active' : 'filter-item' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <ul>
                @forelse ($books as $book)
                    <li class="mb-4">
                        <div class="book-item">
                            <div class="flex flex-wrap items-center justify-between">
                                <div class="w-full flex-grow sm:w-auto">
                                    <a href="{{ route('books.show', $book) }}" class="book-title">{{ $book->title }}</a>
                                    <span class="book-author">by {{ $book->author }}</span>
                                </div>
                                <div>
                                    <div class="book-rating">
                                        {{ number_format($book->reviews_avg_rating, 1) }}
                                    </div>
                                    <div class="book-review-count">
                                        out of {{ $book->reviews_count }}{{ Str::plural('review', $book->reviews_count) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="mb-4">
                        <div class="empty-book-item">
                            <p class="empty-text">No books found</p>
                            <a href="{{ route('books.index') }}" class="reset-link">Reset criteria</a>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</x-app-layout>
