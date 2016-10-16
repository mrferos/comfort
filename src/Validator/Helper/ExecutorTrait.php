<?php
namespace Comfort\Validator\Helper;

use Comfort\Exception\ValidationException;
use Comfort\ValidationError;

trait ExecutorTrait
{
    /**
     * Execute the validation stack and fail on first
     *
     * @param $value
     * @param null $key
     * @return bool|ValidationError|null
     */
    protected function validate($value, $key = null)
    {
        $executor = clone $this;

        if (is_null($value) && $executor->optional) {
            if (is_null($executor->defaultValue)) {
                return null;
            } else {
                $value = $this->defaultValue;
            }
        }

        try {
            reset($executor->validationStack);

            do {
                /** @var callable $validator */
                $validator = current($executor->validationStack);
                $retVal = $validator($value, $key);
                $value = $retVal === null ? $value : $retVal;
            } while (next($executor->validationStack));

            if ($executor->toBool) {
                return true;
            }

            return $value;
        } catch (ValidationException $validationException) {
            if ($executor->toBool) {
                return false;
            }

            return ValidationError::fromException($validationException);
        }
    }
}