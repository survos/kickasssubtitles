<strong>
    {!! $task->isProcessed() ? __('enums.task_status.'.$task->getStatus()->getValue()) : sprintf('%s&nbsp;<img src="%s">', __('enums.task_status.'.$task->getStatus()->getValue()), asset('img/ajax_loader_16x11.gif')) !!}
</strong>
