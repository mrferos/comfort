<?php
namespace ComfortTest\Validator;

use Comfort\Validator\ArrayValidator;
use Comfort\Comfort;

class ArrayValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $comfortMock;
    /**
     * @var ArrayValidator
     */
    protected $arrayValidator;

    public function setUp()
    {
        $this->comfortMock = $this->getMockBuilder(Comfort::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->arrayValidator = new ArrayValidator($this->comfortMock);
        $this->arrayValidator->toBool(true);
    }

    public function testIsArray()
    {
        $result = $this->arrayValidator->__invoke([]);
        $this->assertNotFalse($result);
    }

    public function testIsNotArray()
    {
        $result = $this->arrayValidator->__invoke(5);
        $this->assertFalse($result);
    }

    public function testBelowMin()
    {
        $this->arrayValidator->min(5);
        $result = $this->arrayValidator->__invoke(range(0, 3));
        $this->assertFalse($result);
    }

    public function testAboveMin()
    {
        $this->arrayValidator->min(5);
        $result = $this->arrayValidator->__invoke(range(0, 7));
        $this->assertNotFalse($result);
    }

    public function testBelowMax()
    {
        $this->arrayValidator->max(5);
        $result = $this->arrayValidator->__invoke(range(0, 3));
        $this->assertNotFalse($result);
    }

    public function testAboveMax()
    {
        $this->arrayValidator->max(5);
        $result = $this->arrayValidator->__invoke(range(0, 10));
        $this->assertFalse($result);
    }

    public function testBelowLength()
    {
        $this->arrayValidator->length(5);
        $result = $this->arrayValidator->__invoke(range(0, 2));
        $this->assertFalse($result);
    }

    public function testAboveLength()
    {
        $this->arrayValidator->length(5);
        $result = $this->arrayValidator->__invoke(range(0, 10));
        $this->assertFalse($result);
    }

    public function testIsLength()
    {
        $this->arrayValidator->length(5);
        $result = $this->arrayValidator->__invoke(range(0, 4));
        $this->assertNotFalse($result);
    }

    public function testIsUnique()
    {
        $this->arrayValidator->unique();
        $result = $this->arrayValidator->__invoke(range(0, 10));
        $this->assertNotFalse($result);
    }

    public function testIsNotUnique()
    {
        $this->arrayValidator->unique();
        $result = $this->arrayValidator->__invoke(['a', 'a', 'b', 'c']);
        $this->assertFalse($result);
    }

}