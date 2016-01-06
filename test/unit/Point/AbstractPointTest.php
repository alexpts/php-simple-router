<?php

use PTS\Router\Point;

include_once  dirname(__DIR__) . '/DemoController.php';

class AbstractPointTest extends PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $endPoint = new Point\ControllerPoint([
            'controller' => 'DemoController',
            'action' => 'action'
        ]);

        $callable = $endPoint->getCall();

        $this::assertCount(2, $callable);
        $this->assertInstanceOf('DemoController', $callable[0]);
        $this->assertEquals('action', $callable[1]);
    }

    public function testCreateWithArguments()
    {
        $endPoint = new Point\ControllerPoint([
            'controller' => 'DemoController',
            'action' => 'action',
            'arguments' => [
                'some' => 2,
                'foo' => 'bar'
            ],
            'otherParams' => 'notPass'
        ]);

        $argsEndPoint = $endPoint->getArguments();

        $this->assertCount(2, $argsEndPoint);
        $this->assertEquals(2, $argsEndPoint['some']);
        $this->assertEquals('bar', $argsEndPoint['foo']);

        $this->assertTrue(!isset($this->{'otherParams'}));
    }

    public function testSetArguments()
    {
        $endPoint = new Point\ControllerPoint([
            'controller' => 'DemoController',
            'action' => 'action'
        ]);

        $endPoint->setArguments([
            'some' => 2,
            'foo' => 'bar'
        ]);

        $argsEndPoint = $endPoint->getArguments();

        $this->assertCount(2, $argsEndPoint);
        $this->assertEquals(2, $argsEndPoint['some']);
        $this->assertEquals('bar', $argsEndPoint['foo']);
    }

    public function testSetGetArgument()
    {
        $endPoint = new Point\ControllerPoint([
            'controller' => 'DemoController',
            'action' => 'action'
        ]);

        $endPoint->setArgument('some', 2);

        $argsEndPoint = $endPoint->getArguments();

        $this->assertCount(1, $argsEndPoint);
        $this->assertEquals(2, $argsEndPoint['some']);

        $this->assertEquals(2, $endPoint->getArgument('some'));
    }

    public function testGetCall()
    {
        $endPoint = new Point\ControllerPoint([
            'controller' => 'DemoController',
            'action' => 'action'
        ]);

        $callable = $endPoint->getCall();

        $this->assertCount(2, $callable);
        $this->assertInstanceOf('DemoController', $callable[0]);
        $this->assertEquals('DemoController', get_class($callable[0]));
        $this->assertEquals('action', $callable[1]);

        $this->assertNull($callable[0]->param1);
        $this->assertNull($callable[0]->param2);
    }

    public function testGetCallWithParams()
    {
        $endPoint = new Point\ControllerPoint([
            'controller' => 'DemoController',
            'action' => 'action'
        ]);

        $callable = $endPoint->getCall(['user',358]);

        $this->assertInstanceOf('DemoController', $callable[0]);
        $this->assertEquals('DemoController', get_class($callable[0]));
        $this->assertEquals('action', $callable[1]);

        $this->assertEquals('user', $callable[0]->param1);
        $this->assertEquals(358, $callable[0]->param2);
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Action not found
     */
    public function testBadControllerAction()
    {
        $endPoint = new Point\ControllerPoint([
            'controller' => 'DemoController',
            'action' => 'not_exist_action'
        ]);

        $endPoint->getCall();
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Controller not found
     */
    public function testBadController()
    {
        $endPoint = new Point\ControllerPoint([
            'controller' => 'NotExistDemoController',
            'action' => 'action'
        ]);

        $endPoint->getCall();
    }

    public function testCall()
    {
        $endPoint = new Point\ControllerPoint([
            'controller' => 'DemoController',
            'action' => 'action'
        ]);

        $callable = $endPoint->getCall(['user',358]);
        $response = $endPoint->call($callable, $endPoint->getArguments());

        $this->assertEquals('action', $response);
    }
}