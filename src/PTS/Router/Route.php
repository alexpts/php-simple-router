<?php
namespace PTS\Router;

class Route
{
    const ONLY_XHR = 1;
    const ONLY_NO_XHR = 2;

    /** @var string */
    protected $path;
    /** @var Point\IPoint */
    protected $point;
    /** @var array  */
    protected $restrictions = [];
    /** @var int */
    public $typeRequest;
    /** @var array */
    protected $methods = [];

    /**
     * @param string $path - путь в виде строки/регулярки с плейсхолдерами вида /blog/{slug}. Если нужно указать
     * группу с круглыми скобками, то следует ее указывать как (?:{slug}), чтобы результат группировки не попал в arguments
     *
     * @param Point\IPoint $point
     * @param array $restrictions - ограничения на placeholder - ['slug' => '\d+'], если нет ограничения,
     * то расценивается как *
     *
     * @param int|null $typeRequest
     * @param array $allowHttpMethods
     */
    public function __construct(
        $path,
        Point\IPoint $point,
        array $restrictions = [], $typeRequest = null,
        array $allowHttpMethods = []
    )
    {
        $this->path = $path;
        $this->point = $point;
        $this->methods = array_map('strtoupper', $allowHttpMethods);
        $this->restrictions = $restrictions;
        $this->typeRequest = $typeRequest;
    }


    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return Point\IPoint
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * @return array
     */
    public function getRestrictions()
    {
        return $this->restrictions;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }
}
