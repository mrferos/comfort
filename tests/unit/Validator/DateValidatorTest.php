<?php
namespace ComfortTest\Validator;

use Comfort\Comfort;
use Comfort\ValidationError;
use Comfort\Validator\DateValidator;

class DateValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $comfortMock;
    /**
     * @var DateValidator
     */
    protected $dateValidator;

    public function setUp()
    {
        $this->comfortMock = $this->getMockBuilder(Comfort::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->dateValidator = new DateValidator($this->comfortMock);
        $this->dateValidator->toBool(true);
    }

    public function testIso()
    {
        $this->dateValidator->iso();
        $result = $this->dateValidator->__invoke('2016-05-13T16:01:42-0400');
        $this->assertNotFalse($result);
    }

    public function testNotIso()
    {
        $this->dateValidator->iso();
        $result = $this->dateValidator->__invoke('2016-05-13');
        $this->assertFalse($result);
    }

    public function testTimestamp()
    {
        $this->dateValidator->timestamp();
        $result = $this->dateValidator->__invoke('1463169842');
        $this->assertNotFalse($result);
    }

    public function testNotTimestamp()
    {
        $this->dateValidator->timestamp();
        $result = $this->dateValidator->__invoke('not timestamp');
        $this->assertFalse($result);
    }

    public function testBelowMax()
    {
        $this->dateValidator->max('2016-01-01');
        $result = $this->dateValidator->__invoke('2015-01-01');
        $this->assertNotFalse($result);
    }

    public function testAboveMax()
    {
        $this->dateValidator->max('2016-01-01');
        $result = $this->dateValidator->__invoke('2018-01-01');
        $this->assertFalse($result);
    }

    public function testAboveMin()
    {
        $this->dateValidator->min('2016-01-01');
        $result = $this->dateValidator->__invoke('2018-01-01');
        $this->assertNotFalse($result);
    }

    public function testBelowMin()
    {
        $this->dateValidator->min('2016-01-01');
        $result = $this->dateValidator->__invoke('2015-01-01');
        $this->assertFalse($result);
    }

    public function testCorrectFormat()
    {
        $this->dateValidator->format('Y-m-d');
        $result = $this->dateValidator->__invoke('2015-01-01');
        $this->assertNotFalse($result);
    }

    public function testWrongFormat()
    {
        $this->dateValidator->format('Y-m-d');
        $result = $this->dateValidator->__invoke('01-01-2016');
        $this->assertFalse($result);
    }
}