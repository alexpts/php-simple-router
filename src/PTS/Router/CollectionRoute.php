<?php
namespace PTS\Router;

class CollectionRoute
{
    /** @var [] */
    protected $routes;

    public function __construct()
    {
        $this->routes = [];
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function isHas($name)
    {
        foreach ($this->routes as $priority => $items) {
            if (isset($items[$name])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $name
     * @param Route $route
     * @param int $priority
     * @return $this
     * @throws \Exception
     */
    public function add($name, Route $route, $priority = 50)
    {
        if ($this->isHas($name)) {
            throw new \Exception('Route with the same name already exists');
        }

        $this->routes[$priority][$name] = $route;
        return $this;
    }

    /**
     * @param string $name
     * @param int|null $priority
     * @return CollectionRoute
     */
    public function remove($name, $priority = null)
    {
        if (is_int($priority) && isset($this->routes[$priority][$name])) {
            unset($this->routes[$priority][$name]);
            return $this;
        }

        foreach ($this->routes as $items) {
            if (isset($items[$name])) {
                unset($this->routes[$priority][$name]);
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function clean()
    {
        $this->routes = [];
        return $this;
    }

    /**
     * @return Route[]
     */
    public function getRoutes()
    {
        $listRoutes = [];
        krsort($this->routes, SORT_NUMERIC);

        foreach ($this->routes as $routes) {
            foreach ($routes as $name => $route) {
                $listRoutes[$name] = $route;
            }
        }

        return $listRoutes;
    }
}
