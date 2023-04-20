<?php

namespace App\Repository;

use App\Entity\Song;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Song>
 *
 * @method Song|null find($id, $lockMode = null, $lockVersion = null)
 * @method Song|null findOneBy(array $criteria, array $orderBy = null)
 * @method Song[]    findAll()
 * @method Song[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SongRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Song::class);
    }

    public function save(Song $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Song $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function randomSong(): array
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->where('s.isApproved = :approved')
            ->setParameter('approved', true)
            ->setMaxResults(1)
            ->orderBy('RAND()')
            ->getQuery();
        return $queryBuilder->getResult();
    }

    public function randomHomeSongs(): array
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->where('s.isApproved = :approved')
            ->setParameter('approved', true)
            ->setMaxResults(4)
            ->orderBy('RAND()')
            ->getQuery();
        return $queryBuilder->getResult();
    }

    public function allApprovedSong(): array
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->where('s.isApproved = :approved')
            ->setParameter('approved', true)
            ->orderBy('s.id', 'DESC')
            ->getQuery();

        return $queryBuilder->getResult();
    }

    public function findLikeTitle(string $title): array
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->where('s.title LIKE :title')
            ->andWhere('s.isApproved = :approved')
            ->setParameter('title', '%' . $title . '%')
            ->setParameter('approved', true)
            ->orderBy('s.title', 'ASC')
            ->getQuery();

        return $queryBuilder->getResult();
    }

    public function findLikeApproved(bool $isApproved): array
    {
        $queryBuilder = $this->createQueryBuilder('s');

        if ($isApproved === true) {
            $queryBuilder->where('s.isApproved = :approved')
                ->setParameter('approved', true);
        } elseif ($isApproved === false) {
            $queryBuilder->where('s.isApproved = :approved')
                ->setParameter('approved', false);
        }

        $queryBuilder->orderBy('s.id', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }
}
