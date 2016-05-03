<?php
namespace Sendworks;

class LabelRef
{
    protected $connection;
    protected $label;
    public $url;
    public function __construct($url, $connection = null)
    {
        $this->connection = $connection;
        $this->url = $url;
    }

    public function __get($prop)
    {
        return $this->resolve()->$prop;
    }

    public function __call($fn, $args)
    {
        return call_user_func_array([$this->resolve(), $fn], $args);
    }

    public function resolve()
    {
        if (!$this->label) {
            $this->label = $this->connection->labels->fetch($this);
        }
        return $this->label;
    }

    public function toHash()
    {
        return [
        'url' => $this->url
        ];
    }
}
