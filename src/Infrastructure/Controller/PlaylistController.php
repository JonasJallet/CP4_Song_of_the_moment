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
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/playlist')]
class PlaylistController extends AbstractController
{
    #[Route('/new/{songId}', name: 'playlist_popup_new', methods: ['GET', 'POST'])]
    public function playlistPopupNew(
        int $songId,
        Request $request,
        SongRepository $songRepository,
    ): Response {
        $song = $songRepository->findOneBy(['id' => $songId]);
        $createPlaylistForm = $this->createForm(PlaylistType::class)->handleRequest($request);

        return $this->renderForm('playlist/_playlist_new_popup.html.twig', [
            'song' => $song,
            'createPlaylistForm' => $createPlaylistForm,
        ]);
    }

    #[Route('/new/add/{songId}', name: 'playlist_new_add', methods: ['GET', 'POST'])]
    public function playlistNewAdd(
        int $songId,
        Request $request,
        SongRepository $songRepository,
        PlaylistRepository $playlistRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): Response {
        $song = $songRepository->findOneBy(['id' => $songId]);
        $newPlaylist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class)->handleRequest($request);
        $newPlaylist->setName($formPlaylist->getData()->getName());
        $newPlaylist->setUser($this->getUser());
        $newPlaylist->addSong($song);

        $errors = $validator->validate($newPlaylist);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return $this->json([
                'errors' => $errorMessages
            ], 400); // Code de statut 400 pour une requête incorrecte
        }

        $playlistRepository->save($newPlaylist, true);

        $serializedPlaylist = $serializer->serialize(
            $newPlaylist,
            'json',
            ['groups' => ['default'], 'enable_max_depth' => true]
        );

        return $this->json([
            'newPlaylist' => $serializedPlaylist
        ]);
    }

    #[Route('/{songId}', name: 'playlist_popup', methods: ['GET', 'POST'])]
    public function playlistPopup(
        int $songId,
        UserInterface $user,
        UserRepository $userRepository,
        SongRepository $songRepository,
        PlaylistRepository $playlistRepository
    ): Response {
        $song = $songRepository->findOneBy(['id' => $songId]);
        $user = $userRepository->findOneBy(
            ['id' => $user]
        );
        $playlistsByUser = $user->getPlaylists();
        $collection = [];

        foreach ($playlistsByUser as $playlist) {
            $randomSongs = $playlistRepository->randomSongsByPlaylistId($playlist->getId());

            $collection[$playlist->getId()] = [
                'playlist' => $playlist,
                'songs' => $randomSongs,
            ];
        }

        $this->addFlash('info', 'Test');

        return $this->renderForm('playlist/_playlist_popup.html.twig', [
            'collection' => $collection,
            'song' => $song,
        ]);
    }

    #[Route('/{playlistId}/add/{songId}', name: 'playlist_add', methods: ['GET', 'POST'])]
    public function playlistAdd(
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
            'playlist' => $serializedPlaylist
        ]);
    }
}
