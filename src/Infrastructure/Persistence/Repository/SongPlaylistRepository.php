<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Model\DomainSongPlaylistModelInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Domain\Repository\DomainSongPlaylistRepositoryInterface;
use App\Infrastructure\Persistence\Entity\SongPlaylist;
use Doctrine\Persistence\ManagerRegistry;

class SongPlaylistRepository extends ServiceEntityRepository implements DomainSongPlaylistRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SongPlaylist::class);
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?SongPlaylist
    {
        return parent::findOneBy($criteria);
    }

    public function findAll(): array
    {
        return parent::findAll();
    }

    public function save(DomainSongPlaylistModelInterface $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DomainSongPlaylistModelInterface $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
