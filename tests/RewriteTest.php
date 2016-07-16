<?php

class RewriteTest extends PHPUnit_Framework_TestCase
{
    public function testRewrite()
    {
        $mural = new Monolyth\Mural\Autoloader;
        $mural->rewrite('Foo', 'Bar\Foo');
        $mural->rewrite('', 'Noop\\');
        $foo = new Foo;
        $foobar = new Foo\Bar;
        $baz = new Baz;
        $this->assertEquals('Bar\Foo', get_class($foo));
        $this->assertTrue($foo instanceof Foo);
        $this->assertTrue($foo instanceof Bar\Foo);
        $this->assertEquals('Foo\Bar', get_class($foobar));
        $this->assertEquals('Baz', get_class($baz));
        $dummy = new Dummy;
    }
}

