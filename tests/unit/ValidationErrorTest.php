<?php
namespace ComfortTest;

use Comfort\Exception\ValidationException;
use Comfort\ValidationError;

class ValidationErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testFromException()
    {
        $valException = $this->getMock(
            ValidationException::class,
            [],
            ['key', 'message']
        );

        $valException->expects($this->once())
            ->method('getKey')
            ->willReturn('key');

        // Interesting that it doesn't keep track of the times
        // Called for an internal method.
        // TODO: figure this out
        $valException->expects($this->any())
            ->method('getMessage')
            ->willReturn('message');

        $valError = ValidationError::fromException($valException);
        $this->assertInstanceOf(ValidationError::class, $valError);
        $this->assertEquals('key', $valError->getKey());
        $this->assertEquals('message', $valError->getMessage());
    }

    public function testToString()
    {
        $valError = new ValidationError('key', 'message');

        $this->assertEquals('message', (string)$valError);
    }
}