<?php

namespace App\Infrastructure\Persistence\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Symfony\Component\Uid\Uuid;

class CustomUuidGenerator extends AbstractIdGenerator
{
    public function generate(EntityManager $em, $entity): string
    {
        $uuid = Uuid::v4();

        return $uuid->toBase58();
    }
}
