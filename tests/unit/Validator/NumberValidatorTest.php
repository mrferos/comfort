<?php
namespace ComfortTest\Validator;

use Comfort\Comfort;
use Comfort\Validator\NumberValidator;

class NumberValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $comfortMock;
    /**
     * @var NumberValidator
     */
    protected $numberValidator;

    public function setUp()
    {
        $this->comfortMock = $this->getMockBuilder(Comfort::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->numberValidator = new NumberValidator($this->comfortMock);
        $this->numberValidator->toBool(true);
    }

    public function testBelowMin()
    {
        $this->numberValidator->min(100);
        $result = $this->numberValidator->__invoke(90);
        $this->assertFalse($result);
    }

    public function testAboveMin()
    {
        $this->numberValidator->min(100);
        $result = $this->numberValidator->__invoke(190);
        $this->assertNotTrue($result);
    }

    public function testBelowMax()
    {
        $this->numberValidator->max(100);
        $result = $this->numberValidator->__invoke(90);
        $this->assertNotTrue($result);
    }

    public function testAboveMax()
    {
        $this->numberValidator->max(100);
        $result = $this->numberValidator->__invoke(190);
        $this->assertFalse($result);
    }

    public function testIsPrecision()
    {
        $this->numberValidator->precision(2);
        $result = $this->numberValidator->__invoke(100.20);
        $this->assertNotTrue($result);
    }

    public function testIsNotPrecision()
    {
        $this->numberValidator->precision(2);
        $result = $this->numberValidator->__invoke(100.204);
        $this->assertFalse($result);
    }

    public function testIsInt()
    {
        $this->numberValidator->isInt();
        $result = $this->numberValidator->__invoke(100);
        $this->assertNotTrue($result);
    }

    public function testIsNotInt()
    {
        $this->numberValidator->isInt();
        $result = $this->numberValidator->__invoke(100.204);
        $this->assertFalse($result);
    }

    public function testIsFloat()
    {
        $this->numberValidator->isInt();
        $result = $this->numberValidator->__invoke(100.204);
        $this->assertNotTrue($result);
    }

    public function testIsNotFloat()
    {
        $this->numberValidator->isFloat();
        $result = $this->numberValidator->__invoke(100);
        $this->assertFalse($result);
    }

    public function testIsNumber()
    {
        $this->numberValidator->isNumber();
        $result = $this->numberValidator->__invoke(100.204);
        $this->assertNotTrue($result);
    }

    public function testIsNotNumber()
    {
        $this->numberValidator->isNumber();
        $result = $this->numberValidator->__invoke('not a number');
        $this->assertFalse($result);
    }
}