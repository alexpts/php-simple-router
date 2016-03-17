<?php

use PTS\Router\CollectionRoute;
use PTS\Router\Route;
use PTS\Router\Point;

include_once __DIR__ . '/DemoController.php';

class CollectionRouteTest extends PHPUnit_Framework_TestCase
{
    /** @var CollectionRoute */
    protected $routes;

    protected function setUp()
    {
        $this->routes = new CollectionRoute();
    }

    public function testCreate()
    {
        self::assertCount(0, $this->routes->getRoutes());
    }

    public function testAdd()
    {
        $endPoint = new Point\ControllerPoint([
            'controller' =>'CollectionRouteTest',
            'action' => 'action'
        ]);
        $this->routes->add('default', new Route('/blog/', $endPoint));
        $routes = $this->routes->getRoutes();

        self::assertCount(1, $routes);
        self::assertTrue(isset($routes['default']));
    }

    public function testAddDuplicate()
    {
        $this->setExpectedException(Exception::class, 'Route with the same name already exists');

        $endPoint = new Point\ControllerPoint([
            'controller' =>'DemoController',
            'action' => 'action'
        ]);
        $this->routes->add('default', new Route('/blog/', $endPoint));
        $this->routes->add('default', new Route('/category/', $endPoint));
    }

    public function testClean()
    {
        $endPoint = new Point\ControllerPoint([
            'controller' =>'DemoController',
            'action' => 'action'
        ]);
        $this->routes->add('default', new Route('/blog/', $endPoint));
        $this->routes->clean();
        $routes = $this->routes->getRoutes();

        self::assertCount(0, $routes);
    }

    public function testRemove()
    {
        $endPoint = new Point\ControllerPoint([
            'controller' =>'DemoController',
            'action' => 'action'
        ]);
        $this->routes->add('default', new Route('/blog/', $endPoint));
        $this->routes->remove('default');
        $routes = $this->routes->getRoutes();

        self::assertCount(0, $routes);
    }

    public function testRemoveWithPriority()
    {
        $endPoint = new Point\ControllerPoint([
            'controller' =>'DemoController',
            'action' => 'action'
        ]);
        $this->routes->add('default', new Route('/blog/', $endPoint), 70);
        $this->routes->add('cats', new Route('/cats/', $endPoint), 50);
        $this->routes->remove('default', 70);
        $routes = $this->routes->getRoutes();

        self::assertCount(1, $routes);
    }

    public function testPriority()
    {
        $endPoint = new Point\ControllerPoint([
            'controller' =>'DemoController',
            'action' => 'action'
        ]);
        $this->routes->add('default', new Route('/blog/', $endPoint), 70);
        $this->routes->add('cats', new Route('/cats/', $endPoint), 50);
        $this->routes->add('profile', new Route('/cats/', $endPoint), 60);
        $routes = $this->routes->getRoutes();

        self::assertEquals(['default', 'profile', 'cats'], array_keys($routes));
    }
}