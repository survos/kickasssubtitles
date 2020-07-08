@extends('layouts.master')

<?php $title = __('messages.register'); ?>

@section('title', $title)
@section('heading', $title)

@section('content')

    <form class="app-form" method="post" action="{{ route(App\Enums\Route::REGISTER) }}">

        {{ csrf_field() }}

        <div class="app-form-field -input{{ $errors->has(KickAssSubtitles\Support\UserInterface::USERNAME) ? ' -haserror' : '' }}">
            <label for="{{ KickAssSubtitles\Support\UserInterface::USERNAME }}">{{ __('messages.username') }}*</label>
            <input
                id="{{ KickAssSubtitles\Support\UserInterface::USERNAME }}"
                type="text"
                name="{{ KickAssSubtitles\Support\UserInterface::USERNAME }}"
                value="{{ old(KickAssSubtitles\Support\UserInterface::USERNAME) }}"
                required="required"
                autofocus="autofocus"
            >
            @if ($errors->has(KickAssSubtitles\Support\UserInterface::USERNAME))
                <div class="error">
                    {{ $errors->first(KickAssSubtitles\Support\UserInterface::USERNAME) }}
                </div>
            @endif
            <div class="hint">
                {{ __('messages.username_hint') }}
            </div>
        </div>

        <div class="app-form-field -input{{ $errors->has(KickAssSubtitles\Support\UserInterface::PASSWORD) ? ' -haserror' : '' }}">
            <label for="{{ KickAssSubtitles\Support\UserInterface::PASSWORD }}">{{ __('messages.password') }}*</label>
            <input id="{{ KickAssSubtitles\Support\UserInterface::PASSWORD }}" type="password" name="{{ KickAssSubtitles\Support\UserInterface::PASSWORD }}" required="required">
            @if ($errors->has(KickAssSubtitles\Support\UserInterface::PASSWORD))
                <div class="error">
                    {{ $errors->first(KickAssSubtitles\Support\UserInterface::PASSWORD) }}
                </div>
            @endif
            <div class="hint">
                {{ __('messages.password_hint') }}
            </div>
        </div>

        <div class="app-form-field -input">
            <label for="password-confirm">{{ __('messages.password_confirm') }}*</label>
            <input id="password-confirm" type="password" name="password_confirmation" required="required">
        </div>

        <div class="app-form-field -input{{ $errors->has(KickAssSubtitles\Support\UserInterface::EMAIL) ? ' -haserror' : '' }}">
            <label for="{{ KickAssSubtitles\Support\UserInterface::EMAIL }}">E-mail*</label>
            <input id="{{ KickAssSubtitles\Support\UserInterface::EMAIL }}" type="email" name="{{ KickAssSubtitles\Support\UserInterface::EMAIL }}" value="{{ old(KickAssSubtitles\Support\UserInterface::EMAIL) }}" required="required">
            @if ($errors->has(KickAssSubtitles\Support\UserInterface::EMAIL))
                <div class="error">
                    {{ $errors->first(KickAssSubtitles\Support\UserInterface::EMAIL) }}
                </div>
            @endif
        </div>

        <div class="app-form-field -submit">
            <button type="submit" class="app-button -inverted">
                {{ $title }}
            </button>
        </div>

    </form>

@endsection
