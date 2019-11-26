<?php
namespace JdCps;

class Request
{
    protected $method;
    protected $params;

    public function __construct($method, $params)
    {

        $this->params = $params;
        $this->method = $method;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getParamsJsonString()
    {
        return json_encode($this->params);
    }

}