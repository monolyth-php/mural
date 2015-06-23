<?php

class RewriteTest extends PHPUnit_Framework_TestCase
{
    public function testRewrite()
    {
        $mural = new Mural\Autoloader;
        $mural->rewrite('Foo', 'Bar\Foo');
        $foo = new Foo;
        $foobar = new Foo\Bar;
        $baz = new Baz;
        $this->assertEquals('Bar\Foo', get_class($foo));
        $this->assertEquals('Foo\Bar', get_class($foobar));
        $this->assertEquals('Baz', get_class($baz));
    }
}

