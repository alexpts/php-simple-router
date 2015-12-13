<?php
namespace PTS\Router\Point;

class CallablePoint extends AbstractPoint implements IPoint
{
    /**
     * @param array $params
     * @throws \BadMethodCallException
     */
    public function __construct(array $params)
    {
        if (!isset($params['callable'])) {
            throw new \BadMethodCallException('Bad params');
        }

        if (!is_callable($params['callable'])) {
            throw new \BadMethodCallException('It is not callable');
        }

        parent::__construct($params);
        $this->callable = $params['callable'];
    }

    /**
     * @param array $handlerArgs
     * @return callable
     */
    public function getCall(array $handlerArgs = [])
    {
        return $this->callable;
    }
}