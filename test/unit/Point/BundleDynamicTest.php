<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PTS\Router\Point;

include_once dirname(__DIR__) . '/DemoController.php';
include_once dirname(__DIR__) . '/Bundle/BundleController.php';

class BundleDynamicTest extends TestCase
{

    public function testCreate()
    {
        $endPoint = new Point\DynamicBundleController();

        $endPoint->setArgument('controller', 'Bundle');

        $callable = $endPoint->getCall();

        self::assertCount(2, $callable);
        self::assertInstanceOf('Bundle\BundleController', $callable[0]);
        self::assertEquals('index', $callable[1]);

        self::assertCount(0, $endPoint->getArguments());
    }

    public function testCreateWithArguments()
    {
        $endPoint = new Point\DynamicBundleController([
            'prefix' => ''
        ]);

        $endPoint->setArgument('controller', 'Bundle');
        $endPoint->setArgument('action', 'action');
        $callable = $endPoint->getCall();

        self::assertCount(2, $callable);
        self::assertInstanceOf('Bundle\BundleController', $callable[0]);
        self::assertEquals('action', $callable[1]);

        self::assertCount(0, $endPoint->getArguments());
    }

    public function testWithoutDynamicController()
    {
        static::expectException(BadMethodCallException::class);
        static::expectExceptionMessage('Not found controller name for dynamic controller point');

        $endPoint = new Point\DynamicBundleController();
        $endPoint->getCall();
    }
}