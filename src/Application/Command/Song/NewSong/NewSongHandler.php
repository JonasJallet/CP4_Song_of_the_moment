<?php

namespace App\Application\Command\Song\NewSong;

use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Service\LinkYoutubeFormatInterface;
use App\Domain\Service\SongUploadCoverInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class NewSongHandler
{
    public function __construct(
        public DomainSongRepositoryInterface $songRepository,
        public LinkYoutubeFormatInterface $linkYoutubeFormat,
        public SongUploadCoverInterface $songUploadCover,
        public AuthorizationCheckerInterface $authChecker
    ) {
    }
    public function __invoke(NewSong $newSong): void
    {
        $song = $newSong->song;
        $linkYoutube = $song->getLinkYoutube();
        $linkFormat = $this->linkYoutubeFormat->format($linkYoutube);
        $song->setLinkYoutube($linkFormat);
        $name = $song->getArtist() . ' - ' . $song->getTitle();
        $song->setPhotoAlbum(
            $this->songUploadCover->upload($song->getPhotoAlbum(), $name)
        );
        if ($this->authChecker->isGranted('ROLE_ADMIN')) {
            $song->setIsApproved(true);
        }
        $this->songRepository->save($song, true);
    }
}
