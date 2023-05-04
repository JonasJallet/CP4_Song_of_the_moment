<?php

namespace App\Domain\Service\Manager;

use App\Domain\Model\DomainObjectModelInterface;

interface DomainObjectManagerInterface
{
    public function update(DomainObjectModelInterface $object, array $fields);
}