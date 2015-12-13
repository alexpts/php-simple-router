<?php
namespace PTS\Router\Point;

class DynamicController extends AbstractPoint implements IPoint
{
    /** @var string */
    protected $controllerSlug;
    /** @var  string|null */
    protected $prefix;

    /**
     * @param array $handlerArgs
     * @return callable
     * @throws \BadMethodCallException
     */
    public function getCall(array $handlerArgs = [])
    {
        $arguments = $this->getArguments();
        if (!isset($arguments['controller'])){
            throw new \BadMethodCallException('Not found controller name for dynamic controller point');
        }

        $this->controllerSlug = ucfirst($arguments['controller']);
        unset($arguments['controller']);

        $controller = $this->getControllerClass();
        $this->checkController($controller);

        $action = 'index';
        if (isset($arguments['action'])) {
            $action = $arguments['action'];
            unset($arguments['action']);
        }

        $this->setArguments($arguments);

        $controller = new $controller(... $handlerArgs);
        $this->checkAction($controller, $action);

        return [$controller, $action];
    }

    /**
     * @return string
     */
    protected function getControllerClass()
    {
        return $this->prefix . $this->controllerSlug;
    }
}
