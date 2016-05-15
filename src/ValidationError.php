<?php
namespace Comfort;

use Comfort\Exception\ValidationException;

class ValidationError
{
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $message;

    /**
     * ValidationError constructor.
     *
     * @param string $key
     * @param string $message
     */
    public function __construct($key, $message)
    {
        $this->key = $key;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getMessage();
    }

    /**
     * Create ValidationError instance from an exception
     *
     * @param ValidationException $validationException
     * @return static
     */
    public static function fromException(ValidationException $validationException)
    {
        return new static($validationException->getKey(), $validationException->getMessage());
    }
}