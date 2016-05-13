<?php
namespace Comfort\Validator;

use Comfort\Comfort;
use Comfort\Validator\StringValidator\UriMethod;

class StringValidator extends AbstractValidator
{
    public function __construct(Comfort $comfort)
    {
        parent::__construct($comfort);

        $this->errorHandlers += [
            'string.length' => [
                'message' => '%s is not long enough'
            ],
            'string.alpha' => [
                'message' => '%s is not alpha numeric'
            ]
        ];

        $this->add(function($value, $nameKey) {
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
        $this->add(function($value, $nameKey) {
            preg_match('/[a-zA-Z0-9_]+/', $value, $matches);
            if (!(isset($matches[0]) && ($matches[0] == $value))) {
                $this->createError('string.token', $value, $nameKey);
            }

        });

        return $this;
    }

    /**
     * Validate the string is atleast $min characters
     *
     * @param int $min
     * @return $this
     */
    public function min($min)
    {
        $this->add(function($value, $nameKey) use ($min) {
            if (strlen($value) < $min) {
                $this->createError('string.min', $value, $nameKey);
            }
        });

        return $this;
    }

    /**
     * Validate the string is atleast $max characters
     *
     * @param int $max
     * @return $this
     */
    public function max($max)
    {
        $this->add(function($value, $nameKey) use ($max) {
            if (strlen($value) > $max) {
                return $this->createError('string.max', $value, $nameKey);
            }
        });

        return $this;
    }

    /**
     * Validate the string matches the provided $regex
     *
     * @param string $regex
     * @return $this
     */
    public function matches($regex)
    {
        $this->add(function($value, $nameKey) use ($regex) {
            if (!preg_match($regex, $value)) {
                return $this->createError('string.matches', $value, $nameKey);
            }
        });

        return $this;
    }

    /**
     * Validate the string is _exactly_ $length
     *
     * @param int $length
     */
    public function length($length)
    {
        $this->add(function($value, $nameKey) use ($length) {
            if (strlen($value) != $length) {
                return $this->createError('string.length', $value, $nameKey);
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
        $this->add(function($value, $nameKey) {
            if (!ctype_alnum($value)) {
                return $this->createError('string.alphanum', $value, $nameKey);
            }
        });

        return $this;
    }

    /**
     * Validate the string contains only alpha characters
     *
     * @return $this
     */
    public function alpha()
    {
        $this->add(function($value, $nameKey) {
            if (!ctype_alpha($value)) {
                return $this->createError('string.alpha', $value, $nameKey);
            }
        });

        return $this;
    }

    /**
     * Validate the string is an email
     *
     * @return $this
     */
    public function email()
    {
        $this->add(function($value, $nameKey) {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return $this->createError('string.email', $value, $nameKey);
            }
        });

        return $this;
    }

    /**
     * Validate the string is an IP
     *
     * @return $this
     */
    public function ip()
    {
        $this->add(function($value, $nameKey) {
            if (!filter_var($value, FILTER_VALIDATE_IP)) {
                return $this->createError('string.ip', $value, $nameKey);
            }
        });

        return $this;
    }

    /**
     * Validate the URI is valid
     *
     * @param array $options
     * @return $this
     */
    public function uri(array $options = [])
    {
        $this->add(function ($value) use($options) {
            $method = new UriMethod($options);
            if (!$method($value)) {
                return $this->createError('string.uri', $value, $nameKey);
            }
        });

        return $this;
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
        $this->add(function($value, $nameKey) use($pattern, $replacement) {
            return preg_replace($pattern, $replacement, $value);
        });

        return $this;
    }
}