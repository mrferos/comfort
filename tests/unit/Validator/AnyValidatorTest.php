<?php
namespace ComfortTest\Validator;

use Comfort\Comfort;
use Comfort\Validator\AnyValidator;

class AnyValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $comfortMock;
    /**
     * @var AnyValidator
     */
    protected $anyValidator;

    public function setUp()
    {
        $this->comfortMock = $this->getMockBuilder(Comfort::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->anyValidator = new AnyValidator($this->comfortMock);
        $this->anyValidator->toBool(true);
    }

    public function testInvalidWithValidValues()
    {
        $this->anyValidator->invalid([
            'foo',
            'rawr'
        ]);

        $this->assertTrue($this->anyValidator->__invoke('baz'));
    }

    public function testInvalidWithInvalidValues()
    {
        $this->anyValidator->invalid([
            'foo',
            'rawr'
        ]);

        $this->assertFalse($this->anyValidator->__invoke('rawr'));
    }
}