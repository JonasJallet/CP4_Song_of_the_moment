<?php

namespace App\Domain\Model\ObjectValues;

use App\Domain\Exception\IdObjectValueNotValidException;


class IdObjectValue
{

    public int $incrementNumber;
    private function __construct(int $id)
    {
        //custom logique validation
        if($id>9999)
            throw new IdObjectValueNotValidException();
        $this->incrementNumber = $id;
    }

    public static function fromInt(int $id): self
    {
        return new self($id);
    }
}