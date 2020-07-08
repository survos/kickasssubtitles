@extends('layouts.master')

<?php $title = __('messages.reset_password'); ?>

@section('title', $title)
@section('heading', $title)

@section('content')

    <form class="app-form" method="post" action="{{ route(App\Enums\Route::PASSWORD_EMAIL) }}">

        {{ csrf_field() }}

        <div class="app-form-field -input{{ $errors->has(KickAssSubtitles\Support\UserInterface::EMAIL) ? ' -haserror' : '' }}">
            <label for="{{ KickAssSubtitles\Support\UserInterface::EMAIL }}">E-mail*</label>
            <input
                id="{{ KickAssSubtitles\Support\UserInterface::EMAIL }}"
                type="email"
                name="{{ KickAssSubtitles\Support\UserInterface::EMAIL }}"
                value="{{ old(KickAssSubtitles\Support\UserInterface::EMAIL) }}"
                required="required"
            >
            @if ($errors->has(KickAssSubtitles\Support\UserInterface::EMAIL))
                <div class="error">
                    {{ $errors->first(KickAssSubtitles\Support\UserInterface::EMAIL) }}
                </div>
            @endif
        </div>

        <div class="app-form-field -submit">
            <button type="submit" class="app-button -inverted">
                {{ __('messages.send_password_reset_link') }}
            </button>
        </div>

    </form>

@endsection
