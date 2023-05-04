<?php

namespace App\Domain\Repository;

interface DomainObjectRepositoryInterface
{

    public function find(int $id);

    public function existById(int $id);
}