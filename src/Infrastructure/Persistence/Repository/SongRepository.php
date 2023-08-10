<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Model\DomainSongModelInterface;
use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Infrastructure\Persistence\Entity\Song;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Song|null find($id, $lockMode = null, $lockVersion = null)
 * @method Song[]    findAll()
 * @method Song[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SongRepository extends ServiceEntityRepository implements DomainSongRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Song::class);
    }

    public function save(DomainSongModelInterface $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DomainSongModelInterface $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?DomainSongModelInterface
    {
        return parent::findOneBy($criteria, $orderBy);
    }

    public function randomSong(): array
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->where('s.isApproved = :approved')
            ->andWhere('s.linkYoutubeValid = :valid')
            ->setParameter('approved', true)
            ->setParameter('valid', true)
            ->setMaxResults(1)
            ->orderBy('RAND()')
            ->getQuery();
        return $queryBuilder->getResult();
    }

    public function homeRandomSongs(): array
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->where('s.isApproved = :approved')
            ->andWhere('s.linkYoutubeValid = :valid')
            ->setParameter('approved', true)
            ->setParameter('valid', true)
            ->setMaxResults(4)
            ->orderBy('RAND()')
            ->getQuery();
        return $queryBuilder->getResult();
    }

    public function allValidSong(): array
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->where('s.isApproved = :approved')
            ->andWhere('s.linkYoutubeValid = :valid')
            ->setParameter('approved', true)
            ->setParameter('valid', true)
            ->orderBy('s.id', 'DESC')
            ->getQuery();

        return $queryBuilder->getResult();
    }

    public function findLinkYoutubeInvalid(bool $linkYoutubeValid): array
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->where('s.linkYoutubeValid = :valid')
            ->setParameter('valid', $linkYoutubeValid)
            ->orderBy('s.id', 'DESC')
            ->getQuery();

        return $queryBuilder->getResult();
    }

    public function findTitle(string $title): array
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->where('s.title LIKE :title')
            ->setParameter('title', '%' . $title . '%')
            ->orderBy('s.title', 'ASC')
            ->getQuery();

        return $queryBuilder->getResult();
    }

    public function findLikeValidTitle(?string $term)
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->where('s.title LIKE :term OR s.artist LIKE :term')
            ->andWhere('s.isApproved = :approved')
            ->andWhere('s.linkYoutubeValid = :valid')
            ->setParameter('term', '%' . $term . '%')
            ->setParameter('approved', true)
            ->setParameter('valid', true)
            ->orderBy('s.artist', 'ASC')
            ->addOrderBy('s.title', 'ASC')
            ->getQuery();

        return $queryBuilder->getResult();
    }

    public function findApproved(bool $isApproved): array
    {
        $queryBuilder = $this->createQueryBuilder('s')
                ->where('s.isApproved = :approved')
                ->setParameter('approved', $isApproved)
                ->orderBy('s.id', 'DESC');
        return $queryBuilder->getQuery()->getResult();
    }
}
