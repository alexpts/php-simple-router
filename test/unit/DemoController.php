<?php
declare(strict_types=1);

class DemoController
{
    public $param1;
    public $param2;

    /**
     * @return string
     */
    static public function getAll(): string
    {
        return 'getAll';
    }

    /**
     * @param mixed $param1
     * @param mixed $param2
     */
    public function __construct($param1 = null, $param2 = null)
    {
        $this->param1 = $param1;
        $this->param2 = $param2;
    }

    /**
     * @return string
     */
    public function __invoke(): string
    {
        return $this->index();
    }

    /**
     * @return string
     */
    public function index(): string
    {
        return 'index';
    }

    /**
     * @return string
     */
    public function action(): string
    {
        return 'action';
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return 'get';
    }

    /**
     * @return string
     */
    public function post(): string
    {
        return 'post';
    }

    /**
     * @return string
     */
    public function remove(): string
    {
        return 'remove';
    }

    /**
     * @param string $id
     * @return string
     */
    public function user(string $id): string
    {
        return 'user:' . $id;
    }
}