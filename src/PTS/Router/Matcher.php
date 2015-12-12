<?php
namespace PTS\Router;

use PTS\Router;
use PTS\Router\Point\IPoint;

class Matcher
{
    /**
     * @param CollectionRoute $routes
     * @param string $path
     * @param null|bool $method
     * @param null|bool $isXHR
     * @return \Generator
     * @throws \Exception
     */
    public function match(CollectionRoute $routes, $path, $method = null, $isXHR = null)
    {
        foreach ($routes->getRoutes() as $name => $route) {
            if ($method && !$this->isAllowHttpMethod($method, $route)) {
                continue;
            }

            if ($isXHR !== null && !$this->isAllowRequestType($route, $isXHR)) {
                continue;
            }

            if ($point = $this->matchRule($route, $path)) {
                yield $point;
            }
        }

        throw new \Exception('Not found', 404);
    }

    /**
     * @param Route $route
     * @param bool $isXHR
     * @return bool
     */
    protected function isAllowRequestType(Route $route, $isXHR)
    {
        switch ($route->typeRequest) {
            case $route::ONLY_XHR:
                if (!$isXHR) return false;
                break;
            case $route::ONLY_NO_XHR:
                if ($isXHR) return false;
                break;
        }

        return true;
    }

    /**
     * @param Route $route
     * @param string $pathUrl
     * @return IPoint|false
     */
    protected function matchRule(Route $route, $pathUrl)
    {
        if (preg_match('~^' .  $this->getRegExp($route) . '$~Uiu', $pathUrl, $values)) {
            $filterValues = array_filter(array_keys($values), 'is_string');
            $values = array_intersect_key($values, array_flip($filterValues));

            return $route->endPoint->setArguments($values);
        }

        return false;
    }

    /**
     * @param Route $route
     * @return string
     */
    protected function getRegExp(Route $route)
    {
        $regexp = $route->path;

        if (preg_match_all('~{(.*)}~Uiu', $regexp, $placeholders)) {
            foreach ($placeholders[0] as $index => $match) {
                $name = $placeholders[1][$index];
                $replace = isset($route->restrictions[$name]) ? $route->restrictions[$name] : '.*';
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
    protected function isAllowHttpMethod($method, Route $route)
    {
        if (empty($route->methods)) {
            return true;
        }

        return in_array($method, $route->methods);
    }

}