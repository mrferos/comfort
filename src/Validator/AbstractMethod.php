<?php
namespace Comfort\Validator;

abstract class AbstractMethod
{
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    public function setOptions(array $options)
    {
        foreach ($options as $option => $value) {
            $optionName = $option;
            if (strstr($option, '_')) {
                $option = preg_replace_callback('/_([a-z])/', function($c) {
                    return strtoupper($c[1]);
                }, $option);
            }

            $method = 'set' . ucfirst($option);
            if (!method_exists($this, $method))
                throw new \InvalidArgumentException('Option ' . $optionName . ' is not accepted');

            call_user_func(array($this, $method), $value);
        }
    }
}