<?php
namespace Comfort\Validator;

use Comfort\Comfort;
use Comfort\Validator\Helper\AnyOfTrait;

class StringValidator extends AbstractValidator
{
    use AnyOfTrait;

    public function __construct(Comfort $comfort)
    {
        parent::__construct($comfort);

        $this->errorHandlers += [
            'string.length' => [
                'message' => '%s does not match exactly length'
            ],
            'string.alpha' => [
                'message' => '%s is not alpha numeric'
            ],
            'string.token' => [
                'message' => '%s is not a token'
            ],
            'string.min' => [
                'message' => '%s must be more than % characters long'
            ],
            'string.max' => [
                'message' => '%s must be less than % characters long'
            ],
            'string.alphanum' => [
                'message' => '%s must be alphanumeric'
            ],
            'string.matches' => [
                'message' => '%s does not match %s'
            ],
            'string.email' => [
                'message' => '%s must be an email'
            ],
            'string.ip' => [
                'message' => '%s must be an IP'
            ],
            'string.uri' => [
                'message' => '%s must be a URI'
            ]
        ];

        $this->add(function($value, $nameKey) {
            if (is_null($value)) {
                return;
            }

            if (!is_string($value)) {
                $this->createError('string.type', $value, $nameKey);
            }
        });
    }

    /**
     * Validate the string is a token containing only
     * the character set [a-zA-Z0-9_]
     *
     * @return $this
     */
    public function token()
    {
        return $this->add(function($value, $nameKey) {
            preg_match('/[a-zA-Z0-9_]+/', $value, $matches);
            if (!(isset($matches[0]) && ($matches[0] == $value))) {
                $this->createError('string.token', $value, $nameKey);
            }

        });
    }

    /**
     * Validate the string is atleast $min characters
     *
     * @param int $min
     * @return $this
     */
    public function min($min)
    {
        return $this->add(function($value, $nameKey) use ($min) {
            if (strlen($value) < $min) {
                $this->createError('string.min', $value, $nameKey, $min);
            }
        });
    }

    /**
     * Validate the string is atleast $max characters
     *
     * @param int $max
     * @return $this
     */
    public function max($max)
    {
        return $this->add(function($value, $nameKey) use ($max) {
            if (strlen($value) > $max) {
                return $this->createError('string.max', $value, $nameKey, $max);
            }
        });
    }

    /**
     * Validate the string matches the provided $regex
     *
     * @param string $regex
     * @return $this
     */
    public function matches($regex)
    {
        return $this->add(function($value, $nameKey) use ($regex) {
            if (!preg_match($regex, $value)) {
                return $this->createError('string.matches', $value, $nameKey, $regex);
            }
        });
    }

    /**
     * Validate the string is _exactly_ $length
     *
     * @param int $length
     */
    public function length($length)
    {
        return $this->add(function($value, $nameKey) use ($length) {
            if (strlen($value) != $length) {
                return $this->createError('string.length', $value, $nameKey, $length);
            }
        });
    }

    /**
     * Validate the string contains only alphanumeric characters
     *
     * @return $this
     */
    public function alphanum()
    {
        return $this->add(function($value, $nameKey) {
            if (!ctype_alnum($value)) {
                return $this->createError('string.alphanum', $value, $nameKey);
            }
        });
    }

    /**
     * Validate the string contains only alpha characters
     *
     * @return $this
     */
    public function alpha()
    {
        return $this->add(function($value, $nameKey) {
            if (!ctype_alpha($value)) {
                return $this->createError('string.alpha', $value, $nameKey);
            }
        });
    }

    /**
     * Validate the string is an email
     *
     * @return $this
     */
    public function email()
    {
        return $this->add(function($value, $nameKey) {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return $this->createError('string.email', $value, $nameKey);
            }
        });
    }

    /**
     * Validate the string is an IP
     *
     * @return $this
     */
    public function ip()
    {
        return $this->add(function($value, $nameKey) {
            if (!filter_var($value, FILTER_VALIDATE_IP)) {
                return $this->createError('string.ip', $value, $nameKey);
            }
        });
    }

    /**
     * Validate the URI is valid
     *
     * @return $this
     */
    public function uri()
    {
        return $this->add(function($value, $nameKey) {
            if (!filter_var($value, FILTER_VALIDATE_URL)) {
                return $this->createError('string.uri', $value, $nameKey);
            }
        });
    }


    /**
     * Regex replace the pattern with given replacement
     *
     * @param string $pattern
     * @param string $replacement
     * @return $this
     */
    public function replace($pattern, $replacement)
    {
        return $this->add(function($value, $nameKey) use($pattern, $replacement) {
            return preg_replace($pattern, $replacement, $value);
        });
    }
}