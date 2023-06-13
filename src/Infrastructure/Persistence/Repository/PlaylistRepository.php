<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Infrastructure\Persistence\Entity\Playlist;
use App\Infrastructure\Persistence\Entity\Song;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Playlist>
 *
 * @method Playlist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Playlist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Playlist[]    findAll()
 * @method Playlist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaylistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    public function save(Playlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Playlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function randomSongsByPlaylistId(string $playlistId): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->select('s')
            ->from(Song::class, 's')
            ->join('s.playlists', 'p')
            ->where('p.id = :playlistId')
            ->setParameter('playlistId', $playlistId)
            ->orderBy('RAND()')
            ->setMaxResults(4);

        return $queryBuilder->getQuery()->getResult();
    }
}
