<?php
namespace Comfort\Validator\Helper;

use Comfort\ValidationError;
use Comfort\Validator\AbstractValidator;

trait AlternativesTrait
{
    /**
     * Provide conditional validation in two structures:
     * - alternatives(validator, validator, validator)
     *      Atleast ONE of the supplied validators MUST be valid
     * - alternatives([['if' => validator, 'then' => <validator|value>']])
     *
     * @param $conditions
     * @return $this
     */
    public function alternatives()
    {
        $args = func_get_args();
        $numArgs = func_num_args();

        if ($numArgs == 1) {
            return $this->ifThenElse($args[0]);
        } else {
            return $this->orMethod($args);
        }
    }

    /**
     * Assess a list of validators
     * @param \Comfort\Validator\AbstractValidator[] $conditions
     */
    private function orMethod($conditions)
    {
        return $this->add(function ($value, $nameKey) use ($conditions) {
            $errors = [];
            foreach ($conditions as $condition) {
                if (!$condition instanceof AbstractValidator) {
                    return $this->createError('alternatives.malformed_condition', $value, $nameKey);
                }

                $result = $condition($value);
                if ($result instanceof ValidationError) {
                    $errors[] = $result;
                }
            }

            if (count($errors) === count($conditions)) {
                return $this->createError('alternatives.failed_or', $value, $nameKey);
            }
        });
    }

    /**
     * @param $conditions
     * @return mixed
     */
    private function ifThenElse($conditions)
    {
        return $this->add(function ($value, $nameKey) use ($conditions) {

            foreach ($conditions as $condition) {
                if (!isset($condition['is'])) {
                    return $this->createError('alternatives.missing_is', $value, $nameKey);
                }

                if (!$condition['is'] instanceof AbstractValidator) {
                    return $this->createError('alternatives.invalid_is', $value, $nameKey);
                }

                /** @var AbstractValidator $is */
                $is = $condition['is'];
                $is->toBool(true);

                if (!isset($condition['then'])) {
                    return $this->createError('alternatives.missing_then', $value, $nameKey);
                }

                if ($is($value)) {
                    if ($condition['then'] instanceof AbstractValidator) {
                        $validationStack = $condition['then']->validationStack;
                        foreach ($validationStack as $validator) {
                            $this->validationStack[] = $validator;
                        }
                    } elseif (!is_null($condition['then'])) {
                        return $condition['then'];
                    }
                } elseif (isset($condition['else'])) {
                    if ($condition['else'] instanceof AbstractValidator) {
                        $validationStack = $condition['else']->validationStack;
                        foreach ($validationStack as $validator) {
                            $this->validationStack[] = $validator;
                        }
                    } elseif (!is_null($condition['else'])) {
                        return $condition['else'];
                    }
                }
            }
        });
    }
}