<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractController;
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

/**
 * Class HealthcheckController.
 */
class HealthcheckController extends AbstractController
{
    /**
     * @var Factory
     */
    protected $cache;

    public function __construct(Factory $cache)
    {
        $this->cache = $cache;
    }

    public function cache(): JsonResponse
    {
        $duration = 1; // one minute
        $data = $this->cache->remember('healthcheck', 1, function () {
            $data = [];
            $data['generated'] = Carbon::now()->toISOString();
            $data['value'] = rand(1, 100);

            return $data;
        });

        return response()->json($data);
    }
}
