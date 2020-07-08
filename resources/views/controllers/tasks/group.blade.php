@extends('layouts.master')

<?php $title = ucfirst(__('enums.task_type.' . $tasks->first()->getType()->getValue())); ?>

@section(
    'title',
    sprintf('%s - %s: %s...', $title, lcfirst(__('messages.tasks_group_info')), KickAssSubtitles\Support\Str::uuidFragment($tasks->first()->getGroup()))
)
@section('content.class', 'content _padding-reset')

@section('heading')

    {{ $title }} <span>{{ KickAssSubtitles\Support\Str::uuidFragment($tasks->first()->getGroup()) }}...</span>

@endsection

@section('content')

    <div id="tasks-group">
        @include('partials.tasks.' . $tasks->first()->getType()->getValue())
    </div>

@endsection

@section('scripts')
<script>
    window.fetchTasksGroup();
</script>
@endsection
