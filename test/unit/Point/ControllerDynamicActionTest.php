<?php

use PTS\Router\Point;

include_once  dirname(__DIR__) . '/DemoController.php';

class ControllerDynamicActionTest extends PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $endPoint = new Point\ControllerDynamicAction([
            'controller' => 'DemoController',
        ]);

        $callable = $endPoint->getCall();

        $this->assertCount(2, $callable);
        $this->assertInstanceOf('DemoController', $callable[0]);
        $this->assertEquals('index', $callable[1]);

        $this->assertCount(0, $endPoint->getArguments());
    }

    public function testCreateWithAction()
    {
        $endPoint = new Point\ControllerDynamicAction([
            'controller' => 'DemoController',
        ]);

        $endPoint->setArgument('action', 'action');
        $callable = $endPoint->getCall();

        $this->assertCount(2, $callable);
        $this->assertInstanceOf('DemoController', $callable[0]);
        $this->assertEquals('action', $callable[1]);

        $this->assertCount(0, $endPoint->getArguments());
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Bad params
     */
    public function testCreateWithBadParams()
    {
        new Point\ControllerDynamicAction([]);
    }
}