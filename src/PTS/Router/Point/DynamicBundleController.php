<?php
namespace PTS\Router\Point;

class DynamicBundleController extends DynamicController
{
    /**
     * @return string
     */
    protected function getControllerClass(): string
    {
        return $this->prefix . $this->controllerSlug . '\\' . $this->controllerSlug . 'Controller';
    }
}
