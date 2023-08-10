<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Model\DomainSongFavoriteModelInterface;
use App\Domain\Model\DomainUserModelInterface;
use App\Domain\Repository\DomainSongFavoriteRepositoryInterface;
use App\Infrastructure\Persistence\Entity\SongFavorite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SongFavoriteRepository extends ServiceEntityRepository implements DomainSongFavoriteRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SongFavorite::class);
    }

    public function save(DomainSongFavoriteModelInterface $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DomainSongFavoriteModelInterface $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findFavoritesUser(DomainUserModelInterface $user): array
    {
        $queryBuilder = $this->createQueryBuilder('favorite')
            ->innerJoin('favorite.song', 'song')
            ->where('favorite.user = :user')
            ->andWhere('song.linkYoutubeValid = true')
            ->orderBy('favorite.createdAt', 'DESC')
            ->setParameter('user', $user);

        return $queryBuilder->getQuery()->getResult();
    }
}
