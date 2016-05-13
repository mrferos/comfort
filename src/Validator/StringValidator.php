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
    }

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

    public function length($min = null, $max = null)
    {
        $this->add(function($value, $nameKey) use ($min, $max) {
            switch (true) {
                case !is_null($min) && !is_null($max):
                    if (!(strlen($value) >= $min && strlen($value) <= $max)) {
                        return $this->createError('string.length', $value, $nameKey);
                    }

                    break;
                case !is_null($min):
                    if (!(strlen($value) >= $min)) {
                        return $this->createError('string.length', $value, $nameKey);
                    }

                    break;
                case !is_null($max):
                    if(!(strlen($value) <= $max)) {
                        return $this->createError('string.length', $value, $nameKey);
                    }

                    break;
                default:
                    throw new \RuntimeException('$min and $max cannot both be null');
            }
        });

        return $this;
    }

    public function matches($regex)
    {
        $this->add(function($value, $nameKey) use ($regex) {
            if (!preg_match($regex, $value)) {
                return $this->createError('string.matches', $value, $nameKey);
            }
        });

        return $this;
    }

    public function alphanum()
    {
        $this->add(function($value, $nameKey) {
            if (!ctype_alnum($value)) {
                return $this->createError('string.alphanum', $value, $nameKey);
            }
        });

        return $this;
    }

    public function alpha()
    {
        $this->add(function($value, $nameKey) {
            if (!ctype_alpha($value)) {
                return $this->createError('string.alpha', $value, $nameKey);
            }
        });

        return $this;
    }

    public function email()
    {
        $this->add(function($value, $nameKey) {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return $this->createError('string.email', $value, $nameKey);
            }
        });

        return $this;
    }

    public function ip()
    {
        $this->add(function($value, $nameKey) {
            if (!filter_var($value, FILTER_VALIDATE_IP)) {
                return $this->createError('string.ip', $value, $nameKey);
            }
        });

        return $this;
    }

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

    public function lowercase()
    {
        $this->add(function($value, $nameKey) {
           if(!strtolower($value) == $value) {
               return $this->createError('string.lowercase', $value, $nameKey);
           }
        });

        return $this;
    }

    public function uppercase()
    {
        $this->add(function($value, $nameKey) {
            if(!strtoupper($value) == $value) {
                return $this->createError('string.uppercase', $value, $nameKey);
            }
        });

        return $this;
    }

    public function replace($pattern, $replacement)
    {
        $this->add(function($value, $nameKey) use($pattern, $replacement) {
            return preg_replace($pattern, $replacement, $value);
        });

        return $this;
    }
}