<?php
namespace Comfort;

use Comfort\Exception\ValidationException;

class ValidationError
{
    private $key;
    private $message;

    public function __construct($key, $message)
    {
        $this->key = $key;
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    public function __toString()
    {
        return $this->getMessage();
    }

    public static function fromException(ValidationException $validationException)
    {
        return new static($validationException->getKey(), $validationException->getMessage());
    }
}