<?php

namespace Tracksale\Helpers;

use Tracksale\Configuration\Http;
use Tracksale\Configuration\Routes;
use Tracksale\Exception\InvalidRouteException;

class BuildUrl
{
    /**
     * Build the complete url with base url and desired route
     * 
     * @param string $route
     * @return string
     * @throws InvalidRouteException
     * @throws ReflectionException
     */
    public static function getUrlByRoute(string $route): string
    {
        if (! static::routeExists($route)) {
            throw new InvalidRouteException("Invalid route: " . $route);
        }
        return sprintf("%s/%s", trim(Http::URLS['BASE_URL'], "/"), trim($route, "/"));
    }

    /**
     * Verify if route exists in routes file
     * @param string $route
     * @return bool
     * @throws ReflectionException
     * @throws ReflectionException
     */
    private static function routeExists(string $route): bool
    {
        $reflection = new \ReflectionClass(Routes::class);
        foreach ($reflection->getConstants() as $constant) {
            if ($route == $constant) {
                return true;
            }
        }

        return false;
    }
}
