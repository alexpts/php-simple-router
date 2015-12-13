<?php

use PTS\Router\Point;

include_once  dirname(__DIR__) . '/DemoController.php';

class CallablePointTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Bad params
     */
    public function testCreateWithBadParams()
    {
        new Point\CallablePoint([]);
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage It is not callable
     */
    public function testCreateWithBadParamCallable()
    {
        new Point\CallablePoint([
            'callable' => 'notCallable'
        ]);
    }

    public function testCallInvokeMethodClass()
    {
        $endPoint = new Point\CallablePoint([
            'callable' => new DemoController
        ]);

        $response = $endPoint->call($endPoint->getCall());
        $this->assertEquals('index', $response);
    }

    public function testCallStaticMethod()
    {
        $endPoint = new Point\CallablePoint([
            'callable' => ['DemoController', 'getAll']
        ]);

        $response = $endPoint->call($endPoint->getCall());
        $this->assertEquals('getAll', $response);
    }

    public function testCallFunction()
    {
        $endPoint = new Point\CallablePoint([
            'callable' => 'md5'
        ]);

        $response = $endPoint->call($endPoint->getCall(), ['someMd5']);
        $this->assertEquals(md5('someMd5'), $response);
    }

    public function testCallClosure()
    {
        $endPoint = new Point\CallablePoint([
            'callable' => function(){
                return 'function';
            }
        ]);

        $response = $endPoint->call($endPoint->getCall());
        $this->assertEquals('function', $response);
    }
}