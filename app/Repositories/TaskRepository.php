<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Repositories;

use App\Models\Task;
use App\Models\TaskFilters;
use DateTime;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator as LengthAwarePaginatorAlias;
use KickAssSubtitles\Processor\TaskInterface;
use KickAssSubtitles\Processor\TaskRepository as BaseTaskRepository;
use KickAssSubtitles\Processor\TaskStatus;
use KickAssSubtitles\Processor\TaskType;
use KickAssSubtitles\Support\FiltersInterface;
use KickAssSubtitles\Support\ModelInterface;

/**
 * Class TaskRepository.
 */
class TaskRepository extends BaseTaskRepository
{
    /**
     * {@inheritdoc}
     */
    public function findCount(TaskType $type, TaskStatus $status): int
    {
        $taskClass = $this->taskClass;

        return $taskClass::where([
            $taskClass::TYPE => $type->getValue(),
            $taskClass::STATUS => $status->getValue(),
            $taskClass::PARENT_ID => null,
        ])->count();
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(FiltersInterface $filters): LengthAwarePaginator
    {
        $taskClass = $this->taskClass;
        $query = $taskClass::orderBy($taskClass::CREATED_AT, 'desc');

        /** @var TaskFilters $taskFilters */
        $taskFilters = $filters;

        if ($taskFilters->getGroup()) {
            $query->where($taskClass::GROUP, $taskFilters->getGroup());
        }

        if ($taskFilters->getUser()) {
            /** @var ModelInterface $user */
            $user = $taskFilters->getUser();
            $query->where($taskClass::USER_ID, (int) $user->getId());
        }

        if ($taskFilters->getGroupByGroup()) {
            $db = app(DatabaseManager::class);
            $groups = $db
                ->table($taskClass::getTableName())
                ->select($db->raw(
                    sprintf(
                        '`%s`, MAX(`%s`) as `%s`, MAX(`%s`) as `%s`',
                        $taskClass::GROUP,
                        $taskClass::CREATED_AT,
                        $taskClass::CREATED_AT,
                        $taskClass::TYPE,
                        $taskClass::TYPE
                    )
                ))
                ->whereNotNull($taskClass::GROUP)
                ->groupBy($taskClass::GROUP)
                ->orderBy($taskClass::CREATED_AT, 'desc')
                ->paginate($taskFilters->getLimit())
            ;
            $g = $groups->toArray();
            $query->whereIn(
                $taskClass::GROUP,
                $groups->pluck($taskClass::GROUP)->all()
            );

            return new LengthAwarePaginatorAlias(
                $query->get(),
                $g['total'],
                $g['per_page'],
                $g['current_page'],
                [
                    'path' => $g['path'],
                    'query' => [
                        'page' => $g['current_page'],
                    ],
                ]
            );
        }

        return $query->paginate($taskFilters->getLimit());
    }

    /**
     * {@inheritdoc}
     */
    public function findByIdOrFail(int $id): ModelInterface
    {
        $taskClass = $this->taskClass;

        return $taskClass::findOrFail($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findByIdentifierOrFail(string $identifier): TaskInterface
    {
        $taskClass = $this->taskClass;

        return $taskClass::where(TaskInterface::IDENTIFIER, $identifier)->firstOrFail();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteTasksOlderThan(DateTime $cutOffDate, ?ModelInterface $user = null): void
    {
        $taskClass = $this->taskClass;

        $cutOffDateSql = $cutOffDate->format(ModelInterface::MYSQL_DATETIME);

        $query = $taskClass::whereNull(Task::PARENT_ID)
            ->where(Model::CREATED_AT, '<', $cutOffDateSql)
        ;

        if (null !== $user) {
            $query->where(Task::USER_ID, (int) $user->getId());
        }

        $tasks = $query->get();

        foreach ($tasks as $task) {
            $this->delete($task);
        }
    }
}
