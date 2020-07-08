@extends('layouts.master')

@section('title', sprintf('%s (%s)', $movie->getTitle(), $movie->getYear()))
@section('content.class', 'content _padding-reset')

@section('heading')

    {{ $movie->getTitle() }} <span>{{ $movie->getYear() }}</span>

@endsection

@section('heading.before')

    <div class="poster">
        @if (!$movie->getPoster())
            <img
                src="{{ asset('img/poster_200x300.png') }}"
                alt="{{ __('messages.poster') }}: {{ $movie->getTitle() }}"
            >
        @else
            <img src="{{ $movie->getPoster()->getUrl() }}" alt="{{ __('messages.poster') }}: {{ $movie->getTitle() }}">
        @endif
    </div>

@endsection

@section('heading.after')

    <div class="more">
        {{ __('enums.movie_type.' . $movie->getType()) }}<br>
        <a href="{{ $movie->getImdbId()->getUrl() }}" target="_blank">{{ $movie->getImdbId()->getUrl() }}</a>
    </div>

@endsection

@section('content')

    @if (!$movie->getVideos()->isEmpty())

        <form id="download" method="post" action="{{ route(App\Enums\Route::DOWNLOAD_CREATE) }}">

            {{ csrf_field() }}

            <div class="app-form-field -submit">
                <button type="submit" class="app-button -inverted">
                    <span>{{ __('messages.download') }}</span>
                    <span style="display: none">{{ __('messages.working') }}...</span>
                </button>
            </div>

            <table class="app-table">
                @foreach ($movie->getVideos() as $video)
                    <tbody>
                        <tr>
                            <th>
                                <i class="fa fa-file-video-o fa-2x" aria-hidden="true"></i>
                            </th>
                            <th colspan="5">
                                {{ __('messages.video_file') }}<br>
                                <h2 class="h6">{!! implode('<br>', array_map(function ($el){return '&#10140;&nbsp;'.$el;}, $video->getFilenames())) !!}</h2>
                            </th>
                            <th class="_text-center">
                                {{ __('messages.file_size') }}<br>
                                <strong class="h6">{{ $video->getFilesizeHumanReadable() }}</strong>
                            </th>
                        </tr>
                    </tbody>
                    @if (!$video->getSubtitles()->isEmpty())
                        <tbody class="lines">
                            @foreach ($video->getSubtitles() as $subtitle)
                                <tr class="{{ $loop->last ? 'linesend' : 'lines' }}">
                                    <td>
                                        <i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i>
                                    </td>
                                    <td>
                                        <a
                                            href="#"
                                            class="js-toggle-preview"
                                            data-endpoint="{{ route(App\Enums\Route::SUBTITLES_SHOW, [$subtitle]) }}"
                                        >{{ __('messages.preview') }} <span>+</span><span style="display: none">-</span>
                                        </a>
                                    </td>
                                    <td>
                                        {{ __('messages.provider') }}<br>
                                        <strong>{{ $subtitle->getProvider()->getName() }}</strong>
                                    </td>
                                    <td>
                                        {{ __('messages.language') }}<br>
                                        <strong>{{ __('enums.language.' . $subtitle->getLanguage()) }}</strong>
                                    </td>
                                    <td>
                                        {{ __('messages.output_format') }}<br>
                                        <?php
                                            echo form_select(KickAssSubtitles\Subtitle\SubtitleFormat::class, [
                                                'selected' => $subtitle->getFormat()->getValue(),
                                                'attrs' => [
                                                    'name' => sprintf('items[%s][format]', $subtitle->getRouteKey()),
                                                ],
                                                'label' => function ($enum) {
                                                    $extensions = array_map(function ($extension) {
                                                        return '.' . $extension;
                                                    }, $enum->getExtensions());

                                                    return sprintf(
                                                        '%s (%s)',
                                                        $enum->getName(),
                                                        implode(', ', $extensions)
                                                    );
                                                },
                                            ]);
                                        ?>
                                    </td>
                                    <td>
                                        {{ __('messages.output_encoding') }}<br>
                                        <?php
                                            echo form_select(KickAssSubtitles\Encoding\Encoding::class, [
                                                'selected' => $subtitle->getEncoding()->getValue(),
                                                'attrs' => [
                                                    'name' => sprintf('items[%s][encoding]', $subtitle->getRouteKey()),
                                                ],
                                                'label' => function ($enum) {
                                                    return $enum->getName();
                                                },
                                            ]);
                                        ?>
                                    </td>
                                    <td class="_text-center">
                                        {{ __('messages.download') }}:<br>
                                        <input
                                            type="checkbox"
                                            name="items[{{ $subtitle->getRouteKey() }}][selected]"
                                            value="selected"
                                        >
                                    </td>
                                </tr>
                                <tr style="display: none">
                                    <td colspan="7" class="hint">
                                        <pre class="_margin-reset"></pre>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                @endforeach
            </table>

            <div class="app-form-field -submit">
                <button type="submit" class="app-button -inverted">
                    <span>{{ __('messages.download') }}</span>
                    <span style="display: none">{{ __('messages.working') }}...</span>
                </button>
            </div>

        </form>

    @endif

@endsection
