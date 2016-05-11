<?php
namespace Comfort\Validator;

use Comfort\Comfort;
use Comfort\Error;
use Comfort\Exception\ValidationException;
use Comfort\ValidationError;

class ArrayValidator extends AbstractValidator
{
    public function __construct(Comfort $comfort)
    {
        parent::__construct($comfort);

        $this->toBool(false);
    }

    public function keys(array $definition)
    {
        $this->add(function(&$value) use ($definition) {
            foreach ($value as $key => $val) {
                if (array_key_exists($key, $definition)) {
                    $validator = $definition[$key]->toBool(false);
                    $value[$key] = $validator($val, $key);
                    if ($value[$key] instanceof ValidationError) {
                        throw new ValidationException($value[$key]->getKey(), $value[$key]->getMessage());
                    }
                }
            }

            return $value;
        });

        return $this;
    }
}