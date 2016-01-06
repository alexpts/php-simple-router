<?php

use PTS\Router\CollectionRoute;
use PTS\Router\Route;
use PTS\Router\Point;
use PTS\Router\Matcher;

include_once __DIR__ . '/DemoController.php';

class MatcherTest extends PHPUnit_Framework_TestCase
{

    /** @var CollectionRoute */
    protected $routes;
    /** @var Matcher */
    protected $matcher;

    protected function setUp()
    {
        $this->matcher = new Matcher();
        $this->routes = new CollectionRoute();

        $endPoint = new Point\ControllerDynamicAction([
            'controller' => DemoController::class
        ]);
        $route = new Route('/{lang}/users/{id}/{action}/', $endPoint, [
            'lang' => 'ru|en',
            'action' => '[a-z0-9_]+',
            'id' => '\d+',
        ], Route::ONLY_XHR);
        $this->routes->add('users', $route, 70);

        $endPoint = new Point\ControllerDynamicAction([
            'controller' => DemoController::class
        ]);
        $route = new Route('/blog/{id}/', $endPoint, ['id' => '\d+'], Route::ONLY_NO_XHR, ['get']);
        $this->routes->add('blog', $route, 20);

        $endPoint = new Point\ControllerPoint([
            'controller' => DemoController::class,
            'action' => 'user',
        ]);
        $route = new Route('/profile/{id}(/)?', $endPoint, ['id' => '\d+'], null, ['get']);
        $this->routes->add('profile', $route, 50);

        $endPoint = new Point\CallablePoint([
            'callable' => function () {
                return '404';
            }
        ]);
        $route = new Route('.*', $endPoint, ['id' => '\d+'], null, ['get']);
        $this->routes->add('otherwise', $route, 10);
    }

    public function testSimple()
    {
        $endPoint = $this->matcher->matchFirst($this->routes, '/profile/23/');

        $this->assertEquals('23', $endPoint->getArgument('id'));
        $this->assertCount(1, $endPoint->getArguments());
    }

    public function testHttpMethod()
    {
        $endPoint = $this->matcher->matchFirst($this->routes, '/profile/23/', 'GET');

        $this->assertEquals('23', $endPoint->getArgument('id'));
        $this->assertCount(1, $endPoint->getArguments());
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Not found
     */
    public function testBadHttpMethod()
    {
        $endPoint = $this->matcher->matchFirst($this->routes, '/profile/23/', 'POST');
        $this->assertNull($endPoint);
    }

    public function testOnlyXHR()
    {
        $endPoint = $this->matcher->matchFirst($this->routes, '/ru/users/23/remove/', 'DELETE', true);
        $endPoint->getCall();

        $this->assertEquals('23', $endPoint->getArgument('id'));
        $this->assertEquals('ru', $endPoint->getArgument('lang'));
        $this->assertCount(2, $endPoint->getArguments());
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Not found
     */
    public function testBadOnlyXHR()
    {
        $this->matcher->matchFirst($this->routes, '/ru/users/23/remove/', 'DELETE', false);
    }

    public function testOnlyNoXHR()
    {
        $endPoint = $this->matcher->matchFirst($this->routes, '/blog/23/', null, false);
        $this->assertEquals('23', $endPoint->getArgument('id'));
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Not found
     */
    public function testBadGetOnlyNoXHR()
    {
        $this->matcher->matchFirst($this->routes, '/blog/23/', 'DELETE', true);
    }

    public function testBadOnlyNoXHR()
    {
        $endPoint = $this->matcher->matchFirst($this->routes, '/blog/23/', null, true);
        $response = $endPoint->call($endPoint->getCall());
        $this->assertEquals('404', $response);
    }

    public function testMatch()
    {
        foreach ($this->matcher->match($this->routes, '/ru/users/23/remove/', 'DELETE', true) as $endPoint) {
            /** @var Point\IPoint $endPoint */
            $endPoint->getCall();

            $this->assertEquals('23', $endPoint->getArgument('id'));
            $this->assertEquals('ru', $endPoint->getArgument('lang'));
            $this->assertCount(2, $endPoint->getArguments());
        }
    }
}