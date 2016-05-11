<?php

/**
 * @param $name
 * @param array $args
 * @return \Comfort\Comfort
 */
function cmf()
{
    return (new \Comfort\Factory())->newInstance();
}