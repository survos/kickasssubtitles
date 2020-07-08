@extends('layouts.master')

<?php $title = __('messages.login'); ?>

@section('title', $title)
@section('heading', $title)

@section('content')

    <form class="app-form" method="post" action="{{ route(App\Enums\Route::LOGIN) }}">

        {{ csrf_field() }}

        <div class="app-form-field -input{{ $errors->has(KickAssSubtitles\Support\UserInterface::USERNAME) ? ' -haserror' : '' }}">
            <label for="{{ KickAssSubtitles\Support\UserInterface::USERNAME }}">{{ __('messages.username')  }}*</label>
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
        </div>

        <div class="app-form-field -input{{ $errors->has(KickAssSubtitles\Support\UserInterface::PASSWORD) ? ' -haserror' : '' }}">
            <label for="{{ KickAssSubtitles\Support\UserInterface::PASSWORD }}">{{ __('messages.password') }}*</label>
            <input id="{{ KickAssSubtitles\Support\UserInterface::PASSWORD }}" type="password" name="{{ KickAssSubtitles\Support\UserInterface::PASSWORD }}" required="required">
            @if ($errors->has(KickAssSubtitles\Support\UserInterface::PASSWORD))
                <div class="error">
                    {{ $errors->first(KickAssSubtitles\Support\UserInterface::PASSWORD) }}
                </div>
            @endif
        </div>

        <div class="app-form-field -checkbox">
            <label for="remember">{{ __('messages.remember_me') }}</label>
            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
        </div>

        <div class="app-form-field -submit">
            <button type="submit" class="app-button -inverted">
                {{ $title }}
            </button>
        </div>

    </form>

    <p class="_text-right">
        <a href="{{ route(App\Enums\Route::PASSWORD_REQUEST) }}">
            {{ __('messages.forgot_password') }}
        </a>
    </p>

@endsection
