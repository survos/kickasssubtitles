# Processor

Abstractions for async task processing.

## Usage

Create task:

```php
use KickAssSubtitles\Processor\TaskRepository;
use KickAssSubtitles\Processor\Task;
use KickAssSubtitles\Processor\TaskOptions;
use KickAssSubtitles\Processor\TaskType;

$taskRepository = new TaskRepository(Task::class);
$task = $taskRepository->create(
    new TaskOptions([
        'text' => 'hello',
    ]),
    TaskType::DEFAULT()
);
```

Create processor:

```php
use KickAssSubtitles\Processor\AbstractProcessor;
use KickAssSubtitles\Processor\TaskType;

class EchoProcessor extends AbstractProcessor
{
    /**
     * {@inheritdoc}
     */
    protected function processTask(TaskInterface $task): void
    {
        echo $task->getOptions()->text.PHP_EOL;
    }
}
```

Process task:

```php
$processor = new EchoProcessor();
$processor->processOne($task);
```

