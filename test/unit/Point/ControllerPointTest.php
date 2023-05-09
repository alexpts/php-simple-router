<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PTS\Router\Point;

include_once  dirname(__DIR__) . '/DemoController.php';

class ControllerPointTest extends TestCase
{

    public function testCreate()
    {
        $endPoint = new Point\ControllerPoint([
            'controller' => 'DemoController',
            'action' => 'action'
        ]);

        $callable = $endPoint->getCall();

        $this->assertCount(2, $callable);
        $this->assertInstanceOf('DemoController', $callable[0]);
        $this->assertEquals('action', $callable[1]);

        $this->assertCount(0, $endPoint->getArguments());
    }

    public function testCreateWithoutController()
    {
        static::expectException(BadMethodCallException::class);
        static::expectExceptionMessage('Bad params');

        new Point\ControllerPoint([
            'action' => 'action'
        ]);
    }

    public function testCreateWithoutAction()
    {
        static::expectException(BadMethodCallException::class);
        static::expectExceptionMessage('Bad params');

        new Point\ControllerPoint([
            'controller' => 'DemoController'
        ]);
    }
}