<?php
declare(strict_types=1);

namespace PTS\Router\Point;

abstract class AbstractPoint
{
    /** @var array */
    protected $arguments = [];
    protected $callable;

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        foreach ($params as $name => $param) {
            if (property_exists($this, $name)) {
                $this->{$name} = $param;
            }
        }
    }

    /**
     * @param callable $handler
     * @param array $arguments
     * @return mixed
     */
    public function call(callable $handler, array $arguments = [])
    {
        return call_user_func_array($handler, $arguments);
    }

    /**
     * @param array $args
     * @return $this
     */
    public function setArguments(array $args = [])
    {
        $this->arguments = $args;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setArgument(string $name, $value)
    {
        $this->arguments[$name] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getArgument(string $name)
    {
        return $this->arguments[$name] ?? null;
    }

    /**
     * @param $controller
     * @throws \BadMethodCallException
     */
    protected function checkController($controller)
    {
        if (!class_exists($controller)) {
            throw new \BadMethodCallException('Controller not found');
        }
    }

    /**
     * @param object|string $controller
     * @param string $action
     * @throws \BadMethodCallException
     */
    protected function checkAction($controller, string $action)
    {
        if (!method_exists($controller, $action)) {
            throw new \BadMethodCallException('Action not found');
        }
    }
}
