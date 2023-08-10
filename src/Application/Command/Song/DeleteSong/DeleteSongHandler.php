<?php

namespace App\Application\Command\Song\DeleteSong;

use App\Domain\Repository\DomainSongRepositoryInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class DeleteSongHandler
{
    private KernelInterface $kernel;
    private DomainSongRepositoryInterface $songRepository;

    public function __construct(
        KernelInterface $kernel,
        DomainSongRepositoryInterface $songRepository
    ) {
        $this->kernel = $kernel;
        $this->songRepository = $songRepository;
    }

    public function __invoke(DeleteSong $deleteSong): void
    {
        $song = $this->songRepository->findOneBy(['id' => $deleteSong->songId]);

        if ($song) {
            $coverPath = $this->kernel->getProjectDir() . '/public/songs/covers/' . $song->getPhotoAlbum();

            if (file_exists($coverPath)) {
                unlink($coverPath);
            }

            $this->songRepository->remove($song, true);
        }
    }
}
