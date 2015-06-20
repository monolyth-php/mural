<?php

namespace Mural;

class Autoloader
{
    private $aliases = [];

    public function __construct()
    {
        spl_autoload_register(
            function($class) {
                foreach ($this->aliases as $alias => $actual) {
                    if (strpos($class, $alias) === 0) {
                        class_alias(
                            preg_replace("@^$alias@", $actual, $class),
                            $class
                        );
                        break;
                    }
                }
            },
            true,
            true
        );
    }

    public function alias($requested, $really)
    {
        $this->aliases[$requested] = $really;
    }
}

