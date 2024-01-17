<?php

namespace Pemm\Core;

class Container
{
    private $data = array();
    static private $instance = null;

    /**
     * @return Container
     */
    static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Container();
        }
        return self::$instance;
    }

    public function __clone() {}

    public function get($key)
    {
        return (isset($this->data[$key]) ? $this->data[$key] : null);
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function has($key)
    {
        return isset($this->data[$key]);
    }

}
