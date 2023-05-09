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
    public function call(callable $callable, array $arguments = []);

    /**
     * @param array $args
     * @return $this
     */
    public function setArguments(array $args);

    /**
     * @param string$name
     * @param $value
     * @return $this
     */
    public function setArgument(string $name, $value);

    /**
     * @return array
     */
    public function getArguments(): array;

    /**
     * @param string $name
     * @return mixed
     */
    public function getArgument(string $name);
}
