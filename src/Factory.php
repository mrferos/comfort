<?php
namespace Comfort;

class Factory
{
    /**
     * @return Comfort
     */
    public function newInstance()
    {
        return new Comfort();
    }
}