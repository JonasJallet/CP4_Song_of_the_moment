<?php

namespace App\Domain\Model;

use App\Domain\Model\ObjectValues\IdObjectValue;

interface DomainObjectModelInterface
{


    public function getId():IdObjectValue;

    public function setId(IdObjectValue $id);

}