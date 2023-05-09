<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PTS\Router\Point;

include_once  dirname(__DIR__) . '/DemoController.php';

class CallablePointTest extends TestCase
{

    public function testCreateWithBadParams()
    {
        static::expectException(BadMethodCallException::class);
        static::expectExceptionMessage('Bad params');

        new Point\CallablePoint([]);
    }

    public function testCreateWithBadParamCallable()
    {
        static::expectException(BadMethodCallException::class);
        static::expectExceptionMessage('It is not callable');

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
        self::assertEquals('index', $response);
    }

    public function testCallStaticMethod()
    {
        $endPoint = new Point\CallablePoint([
            'callable' => ['DemoController', 'getAll']
        ]);

        $response = $endPoint->call($endPoint->getCall());
        self::assertEquals('getAll', $response);
    }

    public function testCallFunction()
    {
        $endPoint = new Point\CallablePoint([
            'callable' => 'md5'
        ]);

        $response = $endPoint->call($endPoint->getCall(), ['someMd5']);
        self::assertEquals(md5('someMd5'), $response);
    }

    public function testCallClosure()
    {
        $endPoint = new Point\CallablePoint([
            'callable' => function(){
                return 'function';
            }
        ]);

        $response = $endPoint->call($endPoint->getCall());
        self::assertEquals('function', $response);
    }
}