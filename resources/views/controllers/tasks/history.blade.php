@extends('layouts.master')

<?php $title = __('messages.history'); ?>

@section('title', $title)
@section('heading', $title)
@section('content.class', 'content _padding-reset')

@section('content')

    @if ($tasks->isEmpty())

        @component('components.empty', ['icon' => 'frown-o'])
            {{ __('messages.empty_collection') }}
        @endcomponent

    @else

        @foreach ($tasks->groupBy('group') as $group => $tasksCollection)

            @include('partials.tasks.' . $tasksCollection->first()->getType()->getValue(), ['tasks' => $tasksCollection])

            {{-- <div>
                taskid: {{ $task->getId() }}<br>
                tasktype: {{ $task->getType() }}
                <a href="{{ route('group', [$task->getGroup()]) }}">{{ $task->getGroup() }}</a>
            </div> --}}

        @endforeach

        {{ $tasks->links() }}

    @endif

@endsection
