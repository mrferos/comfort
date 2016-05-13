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
        return $this->add(function(&$value) use ($definition) {
            /**
             * @var string $key
             * @var AbstractValidator $validator
             */
            foreach ($definition as $key => $validator) {
                $validator->toBool(false);
                $validatorValue = isset($value[$key]) ? $value[$key] : null;
                $result = $validator($validatorValue, $key);
                if ($result instanceof ValidationError) {
                    throw new ValidationException($result->getKey(), $result->getMessage());
                }

                if (isset($value[$key])) {
                    $value[$key] = $result;
                }
            }

            return $value;
        });
    }
}