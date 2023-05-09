<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PTS\Router\Point;

include_once  dirname(__DIR__) . '/DemoController.php';

class ControllerDynamicActionTest extends TestCase
{

    public function testCreate()
    {
        $endPoint = new Point\ControllerDynamicAction([
            'controller' => 'DemoController',
        ]);

        $callable = $endPoint->getCall();

        self::assertCount(2, $callable);
        self::assertInstanceOf('DemoController', $callable[0]);
        self::assertEquals('index', $callable[1]);

        self::assertCount(0, $endPoint->getArguments());
    }

    public function testCreateWithAction()
    {
        $endPoint = new Point\ControllerDynamicAction([
            'controller' => 'DemoController',
        ]);

        $endPoint->setArgument('action', 'action');
        $callable = $endPoint->getCall();

        self::assertCount(2, $callable);
        self::assertInstanceOf('DemoController', $callable[0]);
        self::assertEquals('action', $callable[1]);

        self::assertCount(0, $endPoint->getArguments());
    }

    public function testCreateWithBadParams()
    {
        static::expectException(BadMethodCallException::class);
        static::expectExceptionMessage('Bad params');

        new Point\ControllerDynamicAction([]);
    }
}