<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

/**
 * Class LogoComposer.
 */
class LogoComposer
{
    /**
     * @var array
     */
    protected $scenes = [
        'ex_machina',
        'basic_instinct',
        'annihilation',
    ];

    public function compose(View $view): void
    {
        $scene = $this->scenes[array_rand($this->scenes)];
        $view->with('scene', $scene);
    }
}
