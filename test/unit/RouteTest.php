<?php

use PTS\Router\Route;
use PTS\Router\Point;

include_once __DIR__ . '/DemoController.php';

class RouteTest extends PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $endPoint = new Point\ControllerDynamicAction([
            'controller' => 'DemoController'
        ]);
        $route = new Route('demo/{:id}/', $endPoint, [
            'id' => '\d+'
        ], Route::ONLY_XHR, ['get']);


        $this->assertTrue($route->endPoint instanceof Point\IPoint);

        $this->assertEquals('demo/{:id}/', $route->path);

        $this->assertCount(1, $route->methods);
        $this->assertEquals(['GET'], $route->methods);

        $this->assertCount(1, $route->restrictions);
        $this->assertEquals(['id' => '\d+'], $route->restrictions);

        $this->assertEquals(Route::ONLY_XHR, $route->typeRequest);
    }
}