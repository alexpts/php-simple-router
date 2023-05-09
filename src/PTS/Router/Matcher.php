<?php
declare(strict_types=1);

namespace PTS\Router;

use Generator;
use PTS\Router\Point\IPoint;

class Matcher
{
    /**
     * @param CollectionRoute $routes
     * @param string $path
     * @param null|string $method
     * @param bool $isXHR
     * @return Generator
     *
     * @throws NotFoundException
     */
    public function match(CollectionRoute $routes, string $path, ?string $method = null, ?bool $isXHR = null): Generator
    {
        $find = 0;
        foreach ($routes->getRoutes() as $route) {
            $point = $this->matchRoute($route, $path, $method, $isXHR);
            if ($point) {
                $find++;
                yield $point;
            }
        }

        if (!$find) {
            throw new NotFoundException('Not found');
        }
    }

    /**
     * @param Route $route
     * @param string $path
     * @param null|string $method
     * @param null|bool $isXHR
     * @return null|IPoint
     */
    protected function matchRoute(Route $route, string $path, ?string $method = null, ?bool $isXHR = null): ?IPoint
    {
        if ($method !== null && !$this->isAllowHttpMethod($method, $route)) {
            return null;
        }

        if (is_bool($isXHR) && !$this->isAllowRequestType($route, $isXHR)) {
            return null;
        }

        return $this->matchRule($route, $path);
    }

    /**
     * @param CollectionRoute $routes
     * @param string $path
     * @param null|string $method
     * @param null $isXHR
     *
     * @return IPoint|null
     * @throws NotFoundException
     */
    public function matchFirst(CollectionRoute $routes, string $path, ?string $method = null, $isXHR = null): ?IPoint
    {
        return $this->match($routes, $path, $method, $isXHR)->current();
    }

    /**
     * @param Route $route
     * @param bool $isXHR
     * @return bool
     */
    protected function isAllowRequestType(Route $route, bool $isXHR): bool
    {
        switch ($route->typeRequest) {
            case $route::ONLY_XHR:
                if (!$isXHR) { return false; }
                break;
            case $route::ONLY_NO_XHR:
                if ($isXHR) { return false; }
                break;
        }

        return true;
    }

    /**
     * @param Route $route
     * @param string $pathUrl
     * @return IPoint|null
     */
    protected function matchRule(Route $route, string $pathUrl): ?IPoint
    {
        if (preg_match('~^' .  $this->getRegExp($route) . '$~Uiu', $pathUrl, $values)) {
            $filterValues = array_filter(array_keys($values), 'is_string');
            $values = array_intersect_key($values, array_flip($filterValues));

            return $route->getPoint()->setArguments($values);
        }

        return null;
    }

    /**
     * @param Route $route
     * @return string
     */
    protected function getRegExp(Route $route)
    {
        $regexp = $route->getPath();
        $restrictions = $route->getRestrictions();

        if (preg_match_all('~{(.*)}~Uiu', $regexp, $placeholders)) {
            foreach ($placeholders[0] as $index => $match) {
                $name = $placeholders[1][$index];
                $replace = array_key_exists($name, $restrictions) ? $restrictions[$name] : '.*';
                $replace = '(?<'.$name.'>'.$replace.')';
                $regexp = str_replace($match, $replace, $regexp);
            }
        };

        return $regexp;
    }

    /**
     * @param string $method
     * @param Route $route
     * @return bool
     */
    protected function isAllowHttpMethod(string $method, Route $route): bool
    {
        return count($route->getMethods()) === 0 || in_array(strtoupper($method), $route->getMethods(), true);
    }
}
