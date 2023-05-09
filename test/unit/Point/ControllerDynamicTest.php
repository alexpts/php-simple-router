<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PTS\Router\Point;

include_once  dirname(__DIR__) . '/DemoController.php';

class ControllerDynamicTest extends TestCase
{

    public function testCreate()
    {
        $endPoint = new Point\DynamicController([
            'prefix' => 'Demo'
        ]);

        $endPoint->setArgument('controller', 'controller');

        $callable = $endPoint->getCall();

        self::assertCount(2, $callable);
        self::assertInstanceOf('DemoController', $callable[0]);
        self::assertEquals('index', $callable[1]);

        self::assertCount(0, $endPoint->getArguments());
    }

    public function testCreateWithArguments()
    {
        $endPoint = new Point\DynamicController([
            'prefix' => 'Demo'
        ]);

        $endPoint->setArgument('controller', 'controller');
        $endPoint->setArgument('action', 'action');
        $callable = $endPoint->getCall();

        self::assertCount(2, $callable);
        self::assertInstanceOf('DemoController', $callable[0]);
        self::assertEquals('action', $callable[1]);

        self::assertCount(0, $endPoint->getArguments());
    }

    public function testWithoutDynamicController()
    {
        static::expectException(BadMethodCallException::class);
        static::expectExceptionMessage('Not found controller name for dynamic controller point');

        $endPoint = new Point\DynamicController();
        $endPoint->getCall();
    }
}