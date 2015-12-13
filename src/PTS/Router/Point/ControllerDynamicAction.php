<?php
namespace PTS\Router\Point;

class ControllerDynamicAction extends AbstractPoint implements IPoint
{
    /** @var string */
    protected $controller;

    /**
     * @param array $params
     * @throws \BadMethodCallException
     */
    public function __construct(array $params)
    {
        if (!isset($params['controller'])) {
            throw new \BadMethodCallException('Bad params');
        }

        parent::__construct($params);
    }

    /**
     * @param array $handlerArgs
     * @return callable
     */
    public function getCall(array $handlerArgs = [])
    {
        $this->checkController($this->controller);

        $arguments = $this->getArguments();
        $action = 'index';

        if (isset($arguments['action'])) {
            $action = $arguments['action'];
            unset($arguments['action']);
        }

        $this->setArguments($arguments);

        $controller = new $this->controller($handlerArgs);
        $this->checkAction($controller, $action);

        return [$controller, $action];
    }
}
