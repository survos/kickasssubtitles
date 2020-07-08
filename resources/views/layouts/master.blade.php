<!doctype html>
<html dir="ltr" lang="{{ $_localization->getCurrentLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title') @yield('title') |@endif {{ config('app.name') }} | {{ __('messages.tagline') }}</title>
    @include('partials.tracking')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>

    <div class="app-drag-drop-overlay" id="drag-drop-overlay"></div>

    <div class="app-bar">
        {!! $_menu->get('lang')->asUl(['class' => 'app-menu-lang']) !!}
        {!! $_menu->get('auth')->asUl(['class' => 'app-menu-auth']) !!}
    </div>

    <div class="app-sidebar">

        @include('partials.logo')

        <div class="app-sidebar-scrolling" id="scrolling-sidebar">

            {!! $_menu->get('main')->asUl(['class' => 'app-menu']) !!}

            <div class="app-sidebar-section">
                <p>
                    {{ __('messages.open_source', ['project' => config('app.name')]) }}
                </p>
                <div>
                    <a href="https://github.com/kickasssubtitles/kickasssubtitles" target="_blank"><i class="fa fa-github fa-3x" aria-hidden="true"></i></a>
                </div>
            </div>

            <div class="app-sidebar-section">
                <p>
                    {{ __('messages.credits') }}:
                </p>
                <div>
                    <a href="https://www.themoviedb.org/" target="_blank">
                        <img class="logo" src="{{ asset('img/logo_tmdb_208x226.png') }}">
                    </a>
                </div>
            </div>

            <div class="app-sidebar-section">
                <div>
                    {{ __('messages.contact_us') }}: <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>
                </div>
                <div>
                    &copy; 2016-{{ date('Y') }} {{ config('app.name') }}
                </div>
            </div>

            @include('partials.sidebar')

        </div>

    </div>

    <div class="app-content">

        @hasSection('heading')
            <div class="app-heading clearfix">
                @yield('heading.before')
                <div class="title">
                    <h1 class="h4">
                        @yield('heading')
                    </h1>
                </div>
                @yield('heading.after')
            </div>
        @endif

        @if (session('status'))
            <div class="app-flash">
                {{ session('status') }}
            </div>
        @endif

        <div class="@yield('content.class', 'content')">
            @yield('content')
            @include('partials.content')
        </div>

    </div>

    <script src="{{ mix('js/app.js') }}"></script>
    @yield('scripts')

</body>
</html>
