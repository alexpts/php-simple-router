<?php

use PTS\Router\Point;

include_once  dirname(__DIR__) . '/DemoController.php';

class ControllerPointTest extends PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $endPoint = new Point\ControllerPoint([
            'controller' => 'DemoController',
            'action' => 'action'
        ]);

        $callable = $endPoint->getCall();

        $this->assertCount(2, $callable);
        $this->assertInstanceOf('DemoController', $callable[0]);
        $this->assertEquals('action', $callable[1]);

        $this->assertCount(0, $endPoint->getArguments());
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Bad params
     */
    public function testCreateWithoutController()
    {
        $endPoint = new Point\ControllerPoint([
            'action' => 'action'
        ]);
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Bad params
     */
    public function testCreateWithoutAction()
    {
        $endPoint = new Point\ControllerPoint([
            'controller' => 'DemoController'
        ]);
    }
}