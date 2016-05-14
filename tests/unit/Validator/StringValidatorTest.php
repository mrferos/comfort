<?php
namespace ComfortTest\Validator;

use Comfort\Comfort;
use Comfort\Validator\StringValidator;

class StringValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $comfortMock;
    /**
     * @var StringValidator
     */
    protected $stringValidator;

    public function setUp()
    {
        $this->comfortMock = $this->getMockBuilder(Comfort::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->stringValidator = new StringValidator($this->comfortMock);
    }

    public function testIsToken()
    {
        $this->stringValidator->token();
        $result = $this->stringValidator->__invoke('abc019');
        $this->assertNotFalse($result);
    }

    public function testIsNotToken()
    {
        $this->stringValidator->token();
        $result = $this->stringValidator->__invoke('abc!!019');
        $this->assertFalse($result);
    }

    public function testBelowMin()
    {
        $this->stringValidator->min(20);
        $result = $this->stringValidator->__invoke('abc!!019');
        $this->assertFalse($result);
    }

    public function testAboveMin()
    {
        $this->stringValidator->min(2);
        $result = $this->stringValidator->__invoke('abc!!019');
        $this->assertNotFalse($result);
    }

    public function testBelowMax()
    {
        $this->stringValidator->max(20);
        $result = $this->stringValidator->__invoke('abc!!019');
        $this->assertNotFalse($result);
    }

    public function testAboveMax()
    {
        $this->stringValidator->max(2);
        $result = $this->stringValidator->__invoke('abc!!019');
        $this->assertFalse($result);
    }

    public function testDoesMatch()
    {
        $this->stringValidator->matches('/\d+/');
        $result = $this->stringValidator->__invoke('121343');
        $this->assertNotFalse($result);
    }

    public function testDoesNotMatch()
    {
        $this->stringValidator->matches('/\d+/');
        $result = $this->stringValidator->__invoke('abc');
        $this->assertFalse($result);
    }

    public function testStringIsLength()
    {
        $this->stringValidator->length(3);
        $result = $this->stringValidator->__invoke('abc');
        $this->assertNotFalse($result);
    }

    public function testStringIsBelowLength()
    {
        $this->stringValidator->length(3);
        $result = $this->stringValidator->__invoke('ab');
        $this->assertFalse($result);
    }

    public function testStringIsAboveLength()
    {
        $this->stringValidator->length(3);
        $result = $this->stringValidator->__invoke('abcc');
        $this->assertFalse($result);
    }

    public function testStringIsAlphanum()
    {
        $this->stringValidator->alphanum();
        $result = $this->stringValidator->__invoke('abcc123');
        $this->assertNotFalse($result);
    }

    public function testStringIsNotAlphanum()
    {
        $this->stringValidator->alphanum();
        $result = $this->stringValidator->__invoke('abcc123@@@');
        $this->assertFalse($result);
    }

    public function testStringIsAlpha()
    {
        $this->stringValidator->alpha();
        $result = $this->stringValidator->__invoke('abcc');
        $this->assertNotFalse($result);
    }

    public function testStringIsNotAlpha()
    {
        $this->stringValidator->alpha();
        $result = $this->stringValidator->__invoke('abcc123');
        $this->assertFalse($result);
    }

    public function testStringIsEmail()
    {
        $this->stringValidator->email();
        $result = $this->stringValidator->__invoke('abcc123@test.com');
        $this->assertNotFalse($result);
    }

    public function testStringIsNotEmail()
    {
        $this->stringValidator->email();
        $result = $this->stringValidator->__invoke('abcc123');
        $this->assertFalse($result);
    }

    public function testIsIpV4()
    {
        $this->stringValidator->ip();
        $result = $this->stringValidator->__invoke('192.168.100.100');
        $this->assertNotFalse($result);
    }

    public function testIsNotIpV4()
    {
        $this->stringValidator->ip();
        $result = $this->stringValidator->__invoke('123455');
        $this->assertFalse($result);
    }

    public function tedstIsUri()
    {
        $this->stringValidator->uri();
        $result = $this->stringValidator->__invoke('http://www.test.com');
        $this->assertNotFalse($result);
    }

    public function testIsNotUri()
    {
        $this->stringValidator->uri();
        $result = $this->stringValidator->__invoke('123@@@4');
        $this->assertFalse($result);
    }

    public function testReplace()
    {
        $this->stringValidator->replace('/\d+/', 'rawr');
        $result = $this->stringValidator->__invoke('123455');
        $this->assertEquals('rawr', $result);
    }
}