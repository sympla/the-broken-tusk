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
    public function getUrlByRoute(string $route): string
    {
        if (!self::routeExists($route)) {
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
    protected function routeExists(string $route): bool
    {
        foreach (Http::ROUTES as $track_route) {
            if ($route == $track_route) {
                return true;
            }
        }

        return false;
    }
}
