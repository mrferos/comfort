<?php
namespace ComfortTest;

use Comfort\Comfort;
use Comfort\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiatingClass()
    {
        $this->assertInstanceOf(Comfort::class, (new Factory())->newInstance());
    }
}