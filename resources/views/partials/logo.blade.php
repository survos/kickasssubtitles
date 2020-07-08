<div class="app-logo">
    <div class="link">
        <a href="{{ route(App\Enums\Route::HOME) }}">{{ config('app.name') }}</a>
    </div>
    <div class="scene">
        <div class="name">
            {!! KickAssSubtitles\Support\Str::wrap(config('app.name'), '<div><span>') !!}
        </div>
        <img src="/img/scenes/{{ $scene }}_240x240.jpg">
    </div>
    <div class="tagline">
        {{ __('messages.tagline') }}
    </div>
</div>
