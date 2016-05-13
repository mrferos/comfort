<?php

/**
 * @param $name
 * @param array $args
 * @return \Comfort\Comfort
 */
function cmf()
{
    static $factory;
    if ($factory === null) {
        $factory = new \Comfort\Factory();
    }

    return $factory->newInstance();
}