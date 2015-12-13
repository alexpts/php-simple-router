<?php

class DemoController
{
    public $param1;
    public $param2;

    /**
     * @return string
     */
    static public function getAll()
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
    public function __invoke()
    {
        return $this->index();
    }

    /**
     * @return string
     */
    public function index()
    {
        return 'index';
    }

    /**
     * @return string
     */
    public function action()
    {
        return 'action';
    }

    /**
     * @return string
     */
    public function get()
    {
        return 'get';
    }

    /**
     * @return string
     */
    public function post()
    {
        return 'post';
    }

    /**
     * @param string $id
     * @return string
     */
    public function user($id)
    {
        return 'user:' . $id;
    }
}