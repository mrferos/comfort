<?php
namespace ComfortTest\Validator;

use Comfort\Comfort;
use Comfort\ValidationError;
use Comfort\Validator\AbstractValidator;
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

    public function testIsString()
    {
        $result = $this->stringValidator->__invoke('abc019');
        $this->assertNotFalse($result);
    }

    public function testIsNotString()
    {
        $result = $this->stringValidator->__invoke([]);
        $this->assertFalse($result);
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


    /** TODO: really should make a fixture for this */

    public function testErrorMessages()
    {
        $customErrorMessage = 'custom error message';
        $this->stringValidator->toBool(false);
        $this->stringValidator->min(5);
        $this->stringValidator->errorMessages([
            'string.min' => $customErrorMessage
        ]);

        $errorMessages = $this->stringValidator->__invoke('two');
        $this->assertEquals('string.min', $errorMessages->getKey());
        $this->assertEquals($customErrorMessage, $errorMessages->getMessage());
    }

    public function testOptional()
    {
        $this->stringValidator->min(5);
        $this->stringValidator->optional();
        $result = $this->stringValidator->__invoke(null);
        $this->assertNull($result);
    }

    public function testRequired()
    {
        $this->stringValidator->min(5);
        $this->stringValidator->required();
        $result = $this->stringValidator->__invoke(null);
        $this->assertFalse($result);
    }

    public function testDefaultValue()
    {
        $testVal = 'default value';
        $this->stringValidator->defaultValue($testVal);
        $result = $this->stringValidator->__invoke(null);
        $this->assertEquals($testVal, $result);
    }

    public function tsestAlternativesWithoutIs()
    {
        $this->stringValidator->toBool(false);
        $this->stringValidator->alternatives([[]]);
        $result = $this->stringValidator->__invoke('value');
        $this->assertInstanceOf(ValidationError::class, $result);
        $this->assertEquals('alternatives.missing_is', $result->getKey());
    }

    public function testAlternativesIsNotValidator()
    {
        $this->stringValidator->toBool(false);
        $this->stringValidator->alternatives([[
            'is' => 'rawr'
        ]]);
        $result = $this->stringValidator->__invoke('value');
        $this->assertInstanceOf(ValidationError::class, $result);
        $this->assertEquals('alternatives.invalid_is', $result->getKey());
    }

    public function testAlternativesWithoutThen()
    {
        $this->stringValidator->toBool(false);
        $this->stringValidator->alternatives([[
            'is' => $this->getMockBuilder(AbstractValidator::class)
                            ->disableOriginalConstructor()
                            ->getMock()
        ]]);

        $result = $this->stringValidator->__invoke('value');
        $this->assertInstanceOf(ValidationError::class, $result);
        $this->assertEquals('alternatives.missing_then', $result->getKey());
    }

    public function testAlternativesWithValidatorThen()
    {
        $isMock = $this->getMockBuilder(AbstractValidator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $isMock->method('__invoke')
            ->willReturn(true);

        $verificationMessage = 'string';
        $thenMock = $this->getMockBuilder(AbstractValidator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $thenMock->method('__invoke')
            ->with($verificationMessage);

        $this->stringValidator->toBool(false);
        $this->stringValidator->alternatives([[
            'is' => $isMock,
            'then' => $thenMock
        ]]);

        $result = $this->stringValidator->__invoke($verificationMessage);
        $this->assertEquals($verificationMessage, $result);
    }

    public function testAlternativesWithScalarThen()
    {
        $isMock = $this->getMockBuilder(AbstractValidator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $isMock->method('__invoke')
            ->willReturn(true);

        $verificationMessage = 'string';

        $time = microtime();
        $this->stringValidator->toBool(false);
        $this->stringValidator->alternatives([[
            'is' => $isMock,
            'then' => $time
        ]]);

        $result = $this->stringValidator->__invoke($verificationMessage);
        $this->assertEquals($time, $result);
    }

    public function testAlternativesWithValidatorElse()
    {
        $isMock = $this->getMockBuilder(AbstractValidator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $isMock->method('__invoke')
            ->willReturn(false);

        $verificationMessage = 'string';
        $elseMock = $this->getMockBuilder(AbstractValidator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $elseMock->method('__invoke')
            ->with($verificationMessage);

        $this->stringValidator->toBool(false);
        $this->stringValidator->alternatives([[
            'is' => $isMock,
            'then' => false,
            'else' => $elseMock
        ]]);

        $result = $this->stringValidator->__invoke($verificationMessage);
        $this->assertEquals($verificationMessage, $result);
    }

    public function testAlternativesWithScalarElse()
    {
        $isMock = $this->getMockBuilder(AbstractValidator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $isMock->method('__invoke')
            ->willReturn(false);

        $verificationMessage = 'string';
        $elseVal = microtime();
        $this->stringValidator->toBool(false);
        $this->stringValidator->alternatives([[
            'is' => $isMock,
            'then' => false,
            'else' => $elseVal
        ]]);

        $result = $this->stringValidator->__invoke($verificationMessage);
        $this->assertEquals($elseVal, $result);
    }
}