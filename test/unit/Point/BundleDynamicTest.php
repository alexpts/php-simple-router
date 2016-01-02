<?php

use PTS\Router\Point;

include_once  dirname(__DIR__) . '/DemoController.php';
include_once  dirname(__DIR__) . '/Bundle/BundleController.php';

class BundleDynamicTest extends PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $endPoint = new Point\DynamicBundleController();

        $endPoint->setArgument('controller', 'Bundle');

        $callable = $endPoint->getCall();

        $this->assertCount(2, $callable);
        $this->assertInstanceOf('Bundle\BundleController', $callable[0]);
        $this->assertEquals('index', $callable[1]);

        $this->assertCount(0, $endPoint->getArguments());
    }

    public function testCreateWithArguments()
    {
        $endPoint = new Point\DynamicBundleController([
            'prefix' => ''
        ]);

        $endPoint->setArgument('controller', 'Bundle');
        $endPoint->setArgument('action', 'action');
        $callable = $endPoint->getCall();

        $this->assertCount(2, $callable);
        $this->assertInstanceOf('Bundle\BundleController', $callable[0]);
        $this->assertEquals('action', $callable[1]);

        $this->assertCount(0, $endPoint->getArguments());
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Not found controller name for dynamic controller point
     */
    public function testWithoutDynamicController()
    {
        $endPoint = new Point\DynamicBundleController();
        $endPoint->getCall();
    }
}