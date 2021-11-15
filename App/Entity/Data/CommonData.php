<?php

namespace App\Entity\Data;

abstract class CommonData implements CommonDataInterface
{

    public static function fromObject(object $obj): self
    {
        return new static($obj);
    }

    public static function fromArray(array $array): self
    {
        return new static((object) $array);
    }

    public static function fromInstance(CommonDataInterface $obj): self
    {
        return new static($obj);
    }


}