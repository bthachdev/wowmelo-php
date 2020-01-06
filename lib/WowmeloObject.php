<?php

namespace Wowmelo;

class WowmeloObject
{
    public function __construct()
    {

    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function toJSON()
    {
        return json_encode($this);
    }
}