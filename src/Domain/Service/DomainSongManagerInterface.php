<?php

namespace App\Domain\Service\Manager;

use App\Domain\Model\DomainSongModelInterface;

interface DomainSongManagerInterface
{
    public function update(DomainSongModelInterface $object, array $fields);
}
