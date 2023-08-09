<?php

namespace App\Tests\ServiceTest;

use App\Infrastructure\Service\CustomUuidGenerator;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use stdClass;

class CustomUuidGeneratorTest extends TestCase
{
    private CustomUuidGenerator $generator;
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $this->generator = new CustomUuidGenerator();
        $this->entityManager = $this->createMock(EntityManager::class);
    }

    public function testGenerateProducesValidUuid(): void
    {
        $entity = new stdClass();
        $base58Uuid = $this->generator->generate($this->entityManager, $entity);
        $uuid = Uuid::fromBase58($base58Uuid);

        $this->assertInstanceOf(Uuid::class, $uuid);
    }

    public function testGenerateProducesUniqueUuids(): void
    {
        $entity = new stdClass();
        $uuids = [];

        for ($i = 0; $i < 1000; $i++) {
            $base58Uuid = $this->generator->generate($this->entityManager, $entity);
            $uuids[] = $base58Uuid;
        }

        $uniqueUuids = array_unique($uuids);
        $this->assertCount(1000, $uniqueUuids);
    }
}
