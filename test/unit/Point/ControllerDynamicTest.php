<?php

use PTS\Router\Point;

include_once  dirname(__DIR__) . '/DemoController.php';

class ControllerDynamicTest extends PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $endPoint = new Point\DynamicController([
            'prefix' => 'Demo'
        ]);

        $endPoint->setArgument('controller', 'controller');

        $callable = $endPoint->getCall();

        $this->assertCount(2, $callable);
        $this->assertInstanceOf('DemoController', $callable[0]);
        $this->assertEquals('index', $callable[1]);

        $this->assertCount(0, $endPoint->getArguments());
    }

    public function testCreateWithArguments()
    {
        $endPoint = new Point\DynamicController([
            'prefix' => 'Demo'
        ]);

        $endPoint->setArgument('controller', 'controller');
        $endPoint->setArgument('action', 'action');
        $callable = $endPoint->getCall();

        $this->assertCount(2, $callable);
        $this->assertInstanceOf('DemoController', $callable[0]);
        $this->assertEquals('action', $callable[1]);

        $this->assertCount(0, $endPoint->getArguments());
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Not found controller name for dynamic controller point
     */
    public function testWithoutDynamicController()
    {
        $endPoint = new Point\DynamicController();
        $endPoint->getCall();
    }
}