<?php

namespace App\Entity\Data;

interface CommonDataInterface
{
//    function __construct(object $obj);

    public static function fromObject(object $obj): self;

}