<?php
namespace PTS\Router\Point;

class ControllerPoint extends AbstractPoint implements IPoint
{
    /** @var string */
    protected $controller;
    /** @var string */
    protected $action;

    /**
     * @param array $params
     * @throws \BadMethodCallException
     */
    public function __construct(array $params)
    {
        if (!isset($params['controller'], $params['action'])) {
            throw new \BadMethodCallException('Bad params');
        }

        parent::__construct($params);
    }

    /**
     * @param array $handlerArgs
     * @return callable
     * @throws \BadMethodCallException
     */
    public function getCall(array $handlerArgs = [])
    {
        if (!$this->callable) {
            $this->checkController($this->controller);

            $controller = new $this->controller(... $handlerArgs);
            $this->checkAction($controller, $this->action);

            $this->callable = [$controller, $this->action];
        }
        return $this->callable;
    }
}
