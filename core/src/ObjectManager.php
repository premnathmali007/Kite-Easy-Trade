<?php
namespace Core;

class ObjectManager
{
    public function get($classname) {
        return new $classname();
    }
}