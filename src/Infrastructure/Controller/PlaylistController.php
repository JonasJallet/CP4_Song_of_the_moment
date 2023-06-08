<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\Form\PlaylistType;
use App\Infrastructure\Persistence\Entity\Playlist;
use App\Infrastructure\Persistence\Repository\PlaylistRepository;
use App\Infrastructure\Persistence\Repository\SongRepository;
use App\Infrastructure\Persistence\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/playlist')]
class PlaylistController extends AbstractController
{
    #[Route('/{songId}', name: 'playlist_popup', methods: ['GET', 'POST'])]
    public function addToPlaylist(
        int $songId,
        UserInterface $user,
        UserRepository $userRepository,
        SongRepository $songRepository,
    ): Response {
        $song = $songRepository->findOneBy(['id' => $songId]);
        $user = $userRepository->findOneBy(
            ['id' => $user]
        );

        $playlistsByUser = $user->getPlaylists();
        $collection = [];
        foreach ($playlistsByUser as $playlist) {
            $randomSongs = $songRepository->fourRandomSongs();
            $collection[$playlist->getId()] = [
                'playlist' => $playlist,
                'songs' => $randomSongs,
            ];
        }

        return $this->renderForm('playlist/_playlist_popup.html.twig', [
            'collection' => $collection,
            'song' => $song,
        ]);
    }

    #[Route('/{playlistId}/add/{songId}', name: 'playlist_add', methods: ['GET', 'POST'])]
    public function addToPlaylistId(
        int $songId,
        string $playlistId,
        SongRepository $songRepository,
        PlaylistRepository $playlistRepository,
        SerializerInterface $serializer
    ): Response
    {
        $song = $songRepository->findOneBy(['id' => $songId]);
        $playlist = $playlistRepository->findOneBy(['id' => $playlistId]);
        $playlist->addSong($song);
        $playlistRepository->save($playlist, true);

        $serializedPlaylist = $serializer->serialize(
            $playlist,
            'json',
            ['groups' => ['default'], 'enable_max_depth' => true]
        );

        return $this->json([
            'isApproved' => $serializedPlaylist
        ]);
    }

    #[Route('/new/add/{songId}', name: 'app_song_add_to_new_playlist', methods: ['GET', 'POST'])]
    public function addToNewPlaylist(
        int $songId,
        Request $request,
        SongRepository $songRepository,
        PlaylistRepository $playlistRepository
    ): Response {
        $song = $songRepository->findOneBy(['id' => $songId]);
        $playlist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class)->handleRequest($request);

        if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
            $playlist->setName($formPlaylist->getData()->getName());
            $playlist->setUser($this->getUser());
            $playlist->addSong($song);

            $playlistRepository->save($playlist, true);
        }

        return $this->renderForm('song/_add_to_new_playlist.html.twig', [
            'formPlaylist' => $formPlaylist,
        ]);
    }
}
