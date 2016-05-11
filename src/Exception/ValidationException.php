<?php
namespace Comfort\Exception;

class ValidationException extends DiscomfortException
{
    /**
     * @var string
     */
    private $key;

    public function __construct($key, $message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
}