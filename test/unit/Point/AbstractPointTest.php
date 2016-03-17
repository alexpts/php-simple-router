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

        self::assertCount(2, $callable);
        self::assertInstanceOf('DemoController', $callable[0]);
        self::assertEquals('action', $callable[1]);
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

        self::assertCount(2, $argsEndPoint);
        self::assertEquals(2, $argsEndPoint['some']);
        self::assertEquals('bar', $argsEndPoint['foo']);

        self::assertTrue(!isset($this->{'otherParams'}));
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

        self::assertCount(2, $argsEndPoint);
        self::assertEquals(2, $argsEndPoint['some']);
        self::assertEquals('bar', $argsEndPoint['foo']);
    }

    public function testSetGetArgument()
    {
        $endPoint = new Point\ControllerPoint([
            'controller' => 'DemoController',
            'action' => 'action'
        ]);

        $endPoint->setArgument('some', 2);

        $argsEndPoint = $endPoint->getArguments();

        self::assertCount(1, $argsEndPoint);
        self::assertEquals(2, $argsEndPoint['some']);

        self::assertEquals(2, $endPoint->getArgument('some'));
    }

    public function testGetCall()
    {
        $endPoint = new Point\ControllerPoint([
            'controller' => 'DemoController',
            'action' => 'action'
        ]);

        $callable = $endPoint->getCall();

        self::assertCount(2, $callable);
        self::assertInstanceOf('DemoController', $callable[0]);
        self::assertEquals('DemoController', get_class($callable[0]));
        self::assertEquals('action', $callable[1]);

        self::assertNull($callable[0]->param1);
        self::assertNull($callable[0]->param2);
    }

    public function testGetCallWithParams()
    {
        $endPoint = new Point\ControllerPoint([
            'controller' => 'DemoController',
            'action' => 'action'
        ]);

        $callable = $endPoint->getCall(['user',358]);

        self::assertInstanceOf('DemoController', $callable[0]);
        self::assertEquals('DemoController', get_class($callable[0]));
        self::assertEquals('action', $callable[1]);

        self::assertEquals('user', $callable[0]->param1);
        self::assertEquals(358, $callable[0]->param2);
    }

    public function testBadControllerAction()
    {
        $this->setExpectedException(BadMethodCallException::class, 'Action not found');

        $endPoint = new Point\ControllerPoint([
            'controller' => 'DemoController',
            'action' => 'not_exist_action'
        ]);

        $endPoint->getCall();
    }

    public function testBadController()
    {
        $this->setExpectedException(BadMethodCallException::class, 'Controller not found');

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

        self::assertEquals('action', $response);
    }
}