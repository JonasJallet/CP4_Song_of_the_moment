<?php

namespace App\Application\Command\SearchApi\CreateSong;

use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Service\LinkYoutubeSearchInterface;
use App\Domain\Service\SongUploadCoverInterface;
use App\Domain\Model\DomainSongModelInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateSongHandler implements MessageHandlerInterface
{
    public function __construct(
        public DomainSongRepositoryInterface $songRepository,
        public SongUploadCoverInterface $songUploadCover,
        public LinkYoutubeSearchInterface $linkYoutubeSearch,
        public DomainSongModelInterface $songModel,
    ) {
    }

    public function __invoke(CreateSong $createSong): void
    {
        $songsData = $createSong->getSongsData();

        foreach ($songsData as $songData) {
            $existingSongs = $this->songRepository->findBy([
                'artist' => $songData['artist'],
                'title' => $songData['title'],
            ]);

            if (!$existingSongs) {
                $song = new $this->songModel();
                $song->setTitle($songData['title']);
                $song->setArtist($songData['artist']);
                $song->setAlbum($songData['album']);

                $name = $songData['artist'] . ' - ' . $songData['album'] . '.avif';
                $song->setPhotoAlbum(
                    $this->songUploadCover->upload($songData['cover'], $name)
                );

                $song->setLinkYoutube(
                    $this->linkYoutubeSearch->search($songData['artist'], $songData['title'])
                );
                $song->setIsApproved(true);
                $this->songRepository->save($song, true);
            }
        }
    }
}
