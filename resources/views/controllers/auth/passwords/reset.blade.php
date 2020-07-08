@extends('layouts.master')

<?php $title = __('messages.reset_password'); ?>

@section('title', $title)
@section('heading', $title)

@section('content')

    <form class="app-form" method="post" action="{{ route(App\Enums\Route::PASSWORD_REQUEST) }}">

        {{ csrf_field() }}
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="app-form-field -input{{ $errors->has(KickAssSubtitles\Support\UserInterface::EMAIL) ? ' -haserror' : '' }}">
            <label for="{{ KickAssSubtitles\Support\UserInterface::EMAIL }}">E-mail*</label>
            <input
                id="{{ KickAssSubtitles\Support\UserInterface::EMAIL }}"
                type="email"
                name="{{ KickAssSubtitles\Support\UserInterface::EMAIL }}"
                value="{{ $email or old(KickAssSubtitles\Support\UserInterface::EMAIL) }}"
                required="required"
                autofocus="autofocus"
            >
            @if ($errors->has(KickAssSubtitles\Support\UserInterface::EMAIL))
                <div class="error">
                    {{ $errors->first(KickAssSubtitles\Support\UserInterface::EMAIL) }}
                </div>
            @endif
        </div>

        <div class="app-form-field -input{{ $errors->has(KickAssSubtitles\Support\UserInterface::PASSWORD) ? ' -haserror' : '' }}">
            <label for="{{ KickAssSubtitles\Support\UserInterface::PASSWORD }}">{{ __('messages.password') }}*</label>
            <input id="{{ KickAssSubtitles\Support\UserInterface::PASSWORD }}" type="password" class="form-control" name="{{ KickAssSubtitles\Support\UserInterface::PASSWORD }}" required="required">
            @if ($errors->has(KickAssSubtitles\Support\UserInterface::PASSWORD))
                <div class="error">
                    {{ $errors->first(KickAssSubtitles\Support\UserInterface::PASSWORD) }}
                </div>
            @endif
        </div>

        <div class="app-form-field -input{{ $errors->has('password_confirmation') ? ' -haserror' : '' }}">
            <label for="password-confirm">{{ __('messages.password_confirm') }}*</label>
            <input id="password-confirm" type="password" name="password_confirmation" required="required">
            @if ($errors->has('password_confirmation'))
                <div class="error">
                    {{ $errors->first('password_confirmation') }}
                </div>
            @endif
        </div>

        <div class="app-form-field -submit">
            <button type="submit" class="app-button -inverted">
                {{ __('messages.reset_password') }}
            </button>
        </div>

    </form>

@endsection
