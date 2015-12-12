<?php
namespace PTS\Router\Point;

use PTS\Router\NotCallableException;

class Controller extends AbstractPoint implements IPoint
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
     * @return array
     * @throws NotCallableException
     */
    public function getCall(array $handlerArgs = [])
    {
        $this->checkController($this->controller);

        $controller = new $this->controller(... $handlerArgs);
        $this->checkAction($controller, $this->action);

        return [[$controller, $this->action], $this->getArguments()];
    }
}