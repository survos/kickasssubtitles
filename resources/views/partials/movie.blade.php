<div class="app-movie">
    <a href="{{ route(App\Enums\Route::MOVIES_SHOW, [$movie]) }}">
        @if (!$movie->getPoster())
            <img src="{{ asset('img/poster_200x300.png') }}" alt="{{ __('messages.poster') }}: {{ $movie->getTitle() }}">
        @else
            <img src="{{ $movie->getPoster()->getUrl() }}" alt="{{ __('messages.poster') }}: {{ $movie->getTitle() }}">
        @endif
    </a>
    <div class="info">
        <a href="{{ route(App\Enums\Route::MOVIES_SHOW, [$movie]) }}">
            <span class="title">{{ $movie->getTitle() }}</span>
            <span class="year">{{ $movie->getYear() }}</span>
        </a>
    </div>
</div>
