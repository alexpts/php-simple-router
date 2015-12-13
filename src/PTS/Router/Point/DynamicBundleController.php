<?php
namespace PTS\Router\Point;

class DynamicBundleController extends DynamicController implements IPoint
{
    /**
     * @return string
     */
    public function getControllerClass()
    {
        return $this->prefix . $this->controllerSlug . '\\' . $this->controllerSlug . 'Controller';
    }
}
