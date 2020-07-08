<table class="app-table<?php if ($tasks->isProcessed()) : ?> js-tasks-group-processed<?php endif ?>">

    <tbody>
        <tr>
            <th>
                <a
                    title="{{ $tasks->first()->getGroup() }}"
                    href="{{ route(App\Enums\Route::TASKS_GROUP, [$tasks->first()->getGroup()]) }}"
                >
                    <i class="fa fa-exchange fa-2x" aria-hidden="true"></i>
                </a>
            </th>
            <th colspan="5">
                {{ __('messages.created') }}<br>
                <strong>
                    {{ $tasks->first()->getCreatedAt()->timezone(session('timezone')) }}
                    ({{ $tasks->first()->getCreatedAt()->diffForHumans() }})
                </strong>
            </th>
            @if ($tasks->isDownloadable())
                <th title="{{ __('messages.bulk_download') }}" class="action -dark js-remove-animation">
                    <a href="{{ route(App\Enums\Route::TASKS_GROUP_DOWNLOAD, [$tasks->first()->getGroup()]) }}">
                        <i aria-hidden="true" class="fa fa-download fa-2x faa-horizontal animated"></i>
                    </a>
                </th>
            @else
                <th>&nbsp;</th>
            @endif
        </tr>
    </tbody>

    <tbody class="lines">
        @foreach ($tasks as $task)

            <tr class="{{ $loop->last ? 'linesend' : 'lines' }}">
                <td>
                    <i class="fa fa-exchange fa-2x" aria-hidden="true"></i>
                </td>
                <td class="break">
                    {{ __('messages.filename') }}<br>
                    <strong>{{ $task->getOptions()->getFilename() }}</strong>
                </td>
                <td>
                    {{ __('messages.input_encoding') }}<br>
                    <strong>{{ $task->getOptions()->getInputEncoding() ?? __('messages.auto_detect') }}</strong>
                </td>
                <td>
                    {{ __('messages.output_encoding') }}<br>
                    <strong>{{ $task->getOptions()->getEncoding() }}</strong>
                </td>
                <td>
                    {{ __('messages.output_format') }}<br>
                    <strong>{{ $task->getOptions()->getFormat()->getName() }}</strong>
                </td>
                @if ($task->isDownloadable())
                    <td class="app-task-status -{{ $task->getStatus()->getValue() }}">
                        {{ __('messages.status') }}<br>
                        @include('partials.task_status')
                    </td>
                    <td
                        title="{{ __('messages.download') }}"
                        class="app-task-status -completed action -dark js-remove-animation"
                    >
                        <a href="{{ route(App\Enums\Route::TASKS_DOWNLOAD, [$task]) }}">
                            <i aria-hidden="true" class="fa fa-download fa-2x faa-horizontal animated"></i>
                        </a>
                    </td>
                @else
                    <td colspan="2" class="app-task-status -{{ $task->getStatus()->getValue() }}">
                        {{ __('messages.status') }}<br>
                        @include('partials.task_status')
                    </td>
                @endif
            </tr>

            @if ($task->getError())
                <tr>
                    <td colspan="7" class="hint -{{ $task->getStatus()->getValue() }}">
                        {{ __($task->getError()->getMessage()) }}
                    </td>
                </tr>
            @endif

        @endforeach
    </tbody>

</table>
