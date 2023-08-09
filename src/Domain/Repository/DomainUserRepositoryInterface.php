<?php

namespace App\Domain\Repository;

use App\Domain\Model\DomainUserModelInterface;

/**
 * @method DomainUserModelInterface|null find($id, $lockMode = null, $lockVersion = null)
 * @method DomainUserModelInterface[]    findAll()
 * @method DomainUserModelInterface[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface DomainUserRepositoryInterface
{
    public function save(DomainUserModelInterface $entity, bool $flush = false): void;

    public function findOneBy(array $criteria, array $orderBy = null): ?DomainUserModelInterface;
}
