<?php

namespace Mural;

class Autoloader
{
    private $aliases = [];

    public function __construct()
    {
        spl_autoload_register(
            function($class) {
                $class = preg_replace('@^\\*@', '', $class);
                foreach ($this->aliases as $alias => $actual) {
                    if (strpos($class, $alias) === 0) {
                        $new = preg_replace("@^$alias@", $actual, $class);
                        if (class_exists($new)
                            || interface_exists($new)
                            || trait_exists($new)
                        ) {
                            class_alias($new, $class);
                            break;
                        }
                    }
                }
            },
            true,
            true
        );
    }

    public function rewrite($requested, $really)
    {
        $requested = preg_replace('@^\\*@', '', $requested);
        $really = preg_replace('@^\\*@', '', $really);
        $this->aliases[$requested] = $really;
    }
}

