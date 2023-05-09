<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PTS\Router\Route;
use PTS\Router\Point;

include_once __DIR__ . '/DemoController.php';

class RouteTest extends TestCase
{

    public function testCreate()
    {
        $endPoint = new Point\ControllerDynamicAction([
            'controller' => 'DemoController'
        ]);
        $route = new Route('demo/{:id}/', $endPoint, [
            'id' => '\d+'
        ], Route::ONLY_XHR, ['get']);


        self::assertTrue($route->getPoint() instanceof Point\IPoint);

        self::assertEquals('demo/{:id}/', $route->getPath());

        self::assertCount(1, $route->getMethods());
        self::assertEquals(['GET'], $route->getMethods());

        self::assertCount(1, $route->getRestrictions());
        self::assertEquals(['id' => '\d+'], $route->getRestrictions());

        self::assertEquals(Route::ONLY_XHR, $route->typeRequest);
    }
}