<?php
namespace PTS\Router\Point;

interface IPoint
{
    /**
     * @param array $handlerArgs
     * @return callable
     */
    public function getCall(array $handlerArgs = []);

    /**
     * @param callable $callable
     * @param array $arguments
     * @return mixed
     */
    public function call(callable $callable, array $arguments);

    /**
     * @param array $args
     * @return $this
     */
    public function setArguments(array $args);

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function setArgument($name, $value);

    /**
     * @return array
     */
    public function getArguments();

    /**
     * @param string $name
     * @return mixed
     */
    public function getArgument($name);
}
