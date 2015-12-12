<?php

use PTS\Router\CollectionRoute;
use PTS\Router\Route;
use PTS\Router\Point;

class CollectionRouteTest extends PHPUnit_Framework_TestCase
{
    /** @var CollectionRoute */
    protected $routes;

    public function action(){
        return 1;
    }

    protected function setUp()
    {
        $this->routes = new CollectionRoute();
    }

    public function testCreate()
    {
        $this->assertCount(0, $this->routes->getRoutes());
    }

    public function testAdd()
    {
        $endPoint = new Point\Controller([
            'controller' =>'CollectionRouteTest',
            'action' => 'action'
        ]);
        $this->routes->add('default', new Route('/blog/', $endPoint));
        $routes = $this->routes->getRoutes();

        $this->assertCount(1, $routes);
        $this->assertTrue(isset($routes['default']));
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Route with the same name already exists
     */
    public function testAddDuplicate()
    {
        $endPoint = new Point\Controller([
            'controller' =>'CollectionRouteTest',
            'action' => 'action'
        ]);
        $this->routes->add('default', new Route('/blog/', $endPoint));
        $this->routes->add('default', new Route('/category/', $endPoint));
    }

    public function testClean()
    {
        $endPoint = new Point\Controller([
            'controller' =>'CollectionRouteTest',
            'action' => 'action'
        ]);
        $this->routes->add('default', new Route('/blog/', $endPoint));
        $this->routes->clean();
        $routes = $this->routes->getRoutes();

        $this->assertCount(0, $routes);
    }

    public function testRemove()
    {
        $endPoint = new Point\Controller([
            'controller' =>'CollectionRouteTest',
            'action' => 'action'
        ]);
        $this->routes->add('default', new Route('/blog/', $endPoint));
        $this->routes->remove('default');
        $routes = $this->routes->getRoutes();

        $this->assertCount(0, $routes);
    }

    public function testRemoveWithPriority()
    {
        $endPoint = new Point\Controller([
            'controller' =>'CollectionRouteTest',
            'action' => 'action'
        ]);
        $this->routes->add('default', new Route('/blog/', $endPoint), 70);
        $this->routes->add('cats', new Route('/cats/', $endPoint), 50);
        $this->routes->remove('default', 70);
        $routes = $this->routes->getRoutes();

        $this->assertCount(1, $routes);
    }

    public function testPriority()
    {
        $endPoint = new Point\Controller([
            'controller' =>'CollectionRouteTest',
            'action' => 'action'
        ]);
        $this->routes->add('default', new Route('/blog/', $endPoint), 70);
        $this->routes->add('cats', new Route('/cats/', $endPoint), 50);
        $this->routes->add('profile', new Route('/cats/', $endPoint), 60);
        $routes = $this->routes->getRoutes();

        $this->assertEquals(['default', 'profile', 'cats'], array_keys($routes));
    }
}