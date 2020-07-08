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
     * @var null|UserInterface
     */
    protected $user;

    /**
     * @var null|string
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

    /**
     * @param UserInterface $user
     *
     * @return self
     */
    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return null|UserInterface
     */
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    /**
     * @return string|null
     */
    public function getGroup(): ?string
    {
        return $this->group;
    }

    /**
     * @param string|null $group
     *
     * @return self
     */
    public function setGroup(?string $group): self
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @param bool $flag
     *
     * @return self
     */
    public function setGroupByGroup(bool $flag): self
    {
        $this->groupByGroup = $flag;

        return $this;
    }

    /**
     * @return bool
     */
    public function getGroupByGroup(): bool
    {
        return $this->groupByGroup;
    }
}
