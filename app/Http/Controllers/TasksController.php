<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Http\Controllers;

use App\Enums\Filesystem;
use App\Enums\Route;
use App\Jobs\ProcessSubtitleConversionTask;
use App\Jobs\ProcessSubtitleSearchTask;
use App\Models\Task;
use App\Models\TaskFilters;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;
use KickAssSubtitles\Processor\TaskRepositoryInterface;
use KickAssSubtitles\Processor\TaskType;
use KickAssSubtitles\Subtitle\SubtitleConversionOptions;
use KickAssSubtitles\Subtitle\SubtitleInterface;
use KickAssSubtitles\Subtitle\SubtitleRepository;
use KickAssSubtitles\Subtitle\SubtitleRepositoryInterface;
use KickAssSubtitles\Subtitle\SubtitleSearchOptions;
use KickAssSubtitles\Support\Str;
use function Safe\filesize;
use Throwable;

/**
 * Class TasksController.
 */
class TasksController extends AbstractController
{
    /**
     * @var TaskRepositoryInterface
     */
    protected $taskRepository;

    /**
     * @var SubtitleRepositoryInterface
     */
    protected $subtitleRepository;

    /**
     * @var SubtitleRepository
     */
    protected $tablelessSubtitleRepository;

    public function __construct(
        TaskRepositoryInterface $taskRepository,
        SubtitleRepositoryInterface $subtitleRepository,
        SubtitleRepository $tablelessSubtitleRepository
    ) {
        $this->taskRepository = $taskRepository;
        $this->subtitleRepository = $subtitleRepository;
        $this->tablelessSubtitleRepository = $tablelessSubtitleRepository;
    }

    public function search(): Response
    {
        /** @var ResponseFactory $response */
        $response = response();

        return $response->view('controllers.tasks.search');
    }

    /**
     * @throws Throwable
     */
    public function createSearches(Request $request): JsonResponse
    {
        $group = Str::uuid();
        $requestData = $request->all();
        $items = $requestData['items'];

        foreach ($items as $item) {
            /** @var SubtitleSearchOptions $options */
            $options = new SubtitleSearchOptions($item);
            /** @var Task $task */
            $task = $this->taskRepository->create(
                $options,
                TaskType::SUBTITLE_SEARCH()
            );
            $task->setGroup($group);
            if ($request->user()) {
                $task->setUserId((int) $request->user()->getId());
            }
            ProcessSubtitleSearchTask::dispatch($task);
        }

        /** @var ResponseFactory $response */
        $response = response();

        return $response->json([
           'url' => route(Route::TASKS_GROUP, [$group], false),
        ]);
    }

    public function convert(): Response
    {
        /** @var ResponseFactory $response */
        $response = response();

        return $response->view('controllers.tasks.convert');
    }

    /**
     * @throws Throwable
     */
    public function createConversions(Request $request): JsonResponse
    {
        $storage = app(FilesystemManager::class);
        /** @var FilesystemAdapter $disk */
        $disk = $storage->disk(Filesystem::UPLOADS);
        $group = Str::uuid();
        $requestData = $request->all();
        $items = $requestData['items'];

        foreach ($items as $item) {
            $tmpDir = Str::uuid();

            try {
                /** @var UploadedFile $file */
                $file = $item['file'];

                $clientOriginalName = $file->getClientOriginalName();
                if (!$clientOriginalName) {
                    throw new InvalidArgumentException();
                }

                $relativePath = $file->storeAs(
                    $tmpDir,
                    $clientOriginalName,
                    Filesystem::UPLOADS
                );
                $absolutePath = $disk->path('').$relativePath;

                /** @var SubtitleConversionOptions $options */
                $options = new SubtitleConversionOptions($item);
                $options->setFile($absolutePath);
                /** @var Task $task */
                $task = $this->taskRepository->create(
                    $options,
                    TaskType::SUBTITLE_CONVERSION()
                );
                $task->setGroup($group);
                if ($request->user()) {
                    $task->setUserId((int) $request->user()->getId());
                }
                ProcessSubtitleConversionTask::dispatch($task);
                $disk->deleteDirectory($tmpDir);
            } catch (Throwable $e) {
                $disk->deleteDirectory($tmpDir);

                throw $e;
            }
        }

        /** @var ResponseFactory $response */
        $response = response();

        return $response->json([
           'url' => route(Route::TASKS_GROUP, [$group], false),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function createDownloads(Request $request): JsonResponse
    {
        $group = Str::uuid();
        $requestData = $request->all();
        $items = $requestData['items'];

        foreach ($items as $idHash => $item) {
            if (!(isset($item['selected']) && 'selected' === $item['selected'])) {
                continue;
            }

            /** @var SubtitleInterface $subtitle */
            $subtitle = $this->subtitleRepository->findByIdOrFail(
                hashid_decode($idHash)
            );

            $filenameWithoutExtension = $subtitle->getFile(PATHINFO_FILENAME);
            if ($subtitle->getVideo()) {
                $filenameWithoutExtension = pathinfo($subtitle->getVideo()->getFilenames()[0], PATHINFO_FILENAME);
            }

            $subtitleCopy = $this->tablelessSubtitleRepository->createFromSubtitle(
                $subtitle,
                $filenameWithoutExtension
            );

            $options = new SubtitleConversionOptions([
                SubtitleInterface::FORMAT => $item['format'],
                SubtitleInterface::ENCODING => $item['encoding'],
                SubtitleInterface::LANGUAGE => $subtitleCopy->getLanguage()->getValue(),
                SubtitleConversionOptions::INPUT_ENCODING => $subtitleCopy->getEncoding()->getValue(),
                SubtitleConversionOptions::FILENAME => $subtitleCopy->getFile(PATHINFO_BASENAME),
                SubtitleConversionOptions::FILESIZE => filesize($subtitleCopy->getFile()),
            ]);
            $options->setFile($subtitleCopy->getFile());

            $task = $this->taskRepository->create($options, TaskType::SUBTITLE_CONVERSION());

            $task->setGroup($group);
            if ($request->user()) {
                $task->setUserId((int) $request->user()->getId());
            }

            $subtitleCopy->tearDownStorage();
            ProcessSubtitleConversionTask::dispatch($task);
        }

        /** @var ResponseFactory $response */
        $response = response();

        return $response->json([
           'url' => route(Route::TASKS_GROUP, [$group], false),
        ]);
    }

    public function group(Request $request, string $group): Response
    {
        /** @var TaskFilters $filters */
        $filters = new TaskFilters();
        $filters
            ->setGroup($group)
            ->setLimit(1000)
        ;

        $tasks = $this->taskRepository->findAll($filters);
        $tasks = $tasks->getCollection();

        if ($tasks->isEmpty()) {
            throw new ModelNotFoundException();
        }

        $vars = [
            'group' => $group,
            'tasks' => $tasks,
        ];

        /** @var ResponseFactory $response */
        $response = response();

        if ($request->ajax()) {
            $type = $tasks->first()->getType()->getValue();

            return $response->view('partials.tasks.'.$type, $vars);
        }

        return $response->view('controllers.tasks.group', $vars);
    }

    /**
     * @throws Throwable
     */
    public function history(Request $request): Response
    {
        $filters = (new TaskFilters())
            ->setGroupByGroup(true)
            ->setUser($request->user())
            ->setLimit(config('app.history.limit'))
        ;
        $tasks = $this->taskRepository->findAll($filters);

        /** @var ResponseFactory $response */
        $response = response();

        return $response->view('controllers.tasks.history', [
            'tasks' => $tasks,
        ]);
    }
}
