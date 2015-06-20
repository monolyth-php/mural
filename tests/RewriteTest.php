<?php

class RewriteTest extends PHPUnit_Framework_TestCase
{
    public function testRewrite()
    {
        $mural = new Mural\Autoloader;
        $mural->rewrite('\Foo', '\Bar\Foo');
        $foo = new Foo;
        $this->assertEquals('Bar\Foo', get_class($foo));
    }
}

