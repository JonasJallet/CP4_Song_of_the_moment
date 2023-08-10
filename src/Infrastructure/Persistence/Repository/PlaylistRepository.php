<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Model\DomainPlaylistModelInterface;
use App\Domain\Repository\DomainPlaylistRepositoryInterface;
use App\Infrastructure\Persistence\Entity\Playlist;
use App\Infrastructure\Persistence\Entity\Song;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Playlist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Playlist[]    findAll()
 */
class PlaylistRepository extends ServiceEntityRepository implements DomainPlaylistRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    public function save(DomainPlaylistModelInterface $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DomainPlaylistModelInterface $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?DomainPlaylistModelInterface
    {
        return parent::findOneBy($criteria, $orderBy);
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBySlug(string $slug): ?DomainPlaylistModelInterface
    {
        return $this->createQueryBuilder('p')
            ->addSelect('songPlaylist')
            ->addSelect('song')
            ->leftJoin('p.songPlaylists', 'songPlaylist')
            ->leftJoin('songPlaylist.song', 'song')
            ->where('p.slug = :slug')
            ->andWhere('song.linkYoutubeValid = true')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
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
