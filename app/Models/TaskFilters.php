<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Models;

use Illuminate\Http\Request;
use KickAssSubtitles\Support\FiltersInterface;
use KickAssSubtitles\Support\FiltersTrait;
use KickAssSubtitles\Support\UserInterface;

/**
 * Class TaskFilters.
 */
class TaskFilters implements FiltersInterface
{
    use FiltersTrait;

    /**
     * @var UserInterface|null
     */
    protected $user;

    /**
     * @var string|null
     */
    protected $group;

    /**
     * @var bool
     */
    protected $groupByGroup = false;

    /**
     * {@inheritdoc}
     */
    public static function createFromRequest(Request $request): FiltersInterface
    {
        return new static();
    }

    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function getGroup(): ?string
    {
        return $this->group;
    }

    public function setGroup(?string $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function setGroupByGroup(bool $flag): self
    {
        $this->groupByGroup = $flag;

        return $this;
    }

    public function getGroupByGroup(): bool
    {
        return $this->groupByGroup;
    }
}
