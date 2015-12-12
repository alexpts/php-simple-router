<?php
namespace PTS\Router;

class Route
{
    const ONLY_XHR = 1;
    const ONLY_NO_XHR = 2;

    /** @var string */
    public $path;
    /** @var Point\IPoint */
    public $endPoint;
    /** @var array  */
    public $restrictions = [];
    /** @var int */
    public $typeRequest;
    /** @var array */
    public $methods = [];

    /**
     * @param string $path - путь в виде строки/регулярки с плейсхолдерами вида /blog/{slug}. Если нужно указать группу с круглыми скобками, то следует ее указывать как (?:{slug}), чтобы результат группировки не попал в arguments
     * @param Point\IPoint $endPoint
     * @param array $restrictions - ограничения на placeholder - ['slug' => '\d+'], если нет ограничения, то расценивается как *
     * @param int|null $typeRequest
     * @param array $allowHttpMethods
     */
    public function __construct($path, Point\IPoint $endPoint,
        array $restrictions = [], $typeRequest = null,
        array $allowHttpMethods = []
    )
    {
        $this->path = $path;
        $this->endPoint = $endPoint;
        $this->methods = array_map('strtoupper', $allowHttpMethods);
        $this->restrictions = $restrictions;
        $this->typeRequest = $typeRequest;
    }
}