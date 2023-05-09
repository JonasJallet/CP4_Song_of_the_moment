<?php

use App\Domain\Model\DomainObjectModelInterface;
use App\Domain\Service\Manager\DomainObjectManagerInterface;
use Doctrine\ORM\EntityManagerInterface;

class InfrastructureObjectManager implements DomainObjectManagerInterface
{

    public function __construct(
        private readonly EntityManagerInterface                  $entityManager
    )
    {
    }

    public function update(DomainObjectModelInterface $object, array $fields)
    {
        if (isset($fields['field'])) {
            $object->setField($fields['field']);
        }
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
}
