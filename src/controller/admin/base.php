<?php


class SCI_Base
{
    protected $dir;

    public function __construct()
    {
        $this->dir = dirname(__FILE__, 3);
    }
}