<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Class RouteBinds
 * @package App\Services
 */
class RouteBinds
{
    public function bindId($value, $route)
    {
        return hashid_decode($value);
    }
}
