<?php

namespace App\Domain\Repository;

interface DomainUserRepositoryInterface
{
    public function findOneBy(array $criteria);
}
