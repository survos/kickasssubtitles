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

use App\Notifications\ResetPassword;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use KickAssSubtitles\Support\ModelInterface;
use KickAssSubtitles\Support\ModelTrait;
use KickAssSubtitles\Support\UserInterface;

/**
 * Class User.
 *
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 */
class User extends Authenticatable implements ModelInterface, UserInterface
{
    use Notifiable;
    use ModelTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::USERNAME,
        self::EMAIL,
        self::PASSWORD,
        self::ACTIVATION_TOKEN,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        self::PASSWORD,
        self::REMEMBER_TOKEN,
    ];

    /**
     * {@inheritdoc}
     */
    public function isTemporary(): bool
    {
        return null === $this->getEmail();
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->getAttribute(static::USERNAME);
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail(): ?string
    {
        return $this->getAttribute(static::EMAIL);
    }

    /**
     * {@inheritdoc}
     */
    public function getActivationToken(): string
    {
        return $this->getAttribute(static::ACTIVATION_TOKEN);
    }

    /**
     * {@inheritdoc}
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }
}
