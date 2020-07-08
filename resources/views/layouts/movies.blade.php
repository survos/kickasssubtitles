@extends('layouts.master')

@section('content')

    @if ($movies->isEmpty())

        @component('components.empty', ['icon' => 'frown-o'])
            {{ __('messages.empty_collection') }}
        @endcomponent

    @else

        {!! $filters->render() !!}

        <div class="row no-gutters app-chessboard">
            @foreach($movies as $movie)
                <div class="col-md-3 square">
                    @include('partials.movie')
                </div>
            @endforeach
        </div>

        {{ $movies->appends(request()->query())->links() }}

    @endif

@endsection
