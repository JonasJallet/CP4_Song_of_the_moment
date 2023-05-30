<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\Persistence\Repository\PlaylistRepository;
use App\Infrastructure\Persistence\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{
    #[Route('/my-song', name: 'app_user_song', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function mySong(): Response
    {
        return $this->render('user/my_song.html.twig');
    }

    #[Route('/favorite', name: 'app_user_favorite', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function showFavorites(
        UserInterface $user,
        UserRepository $userRepository
    ): Response {
        $userFavorite = $userRepository->findOneBy(
            ['id' => $user]
        );

        $favorites = $userFavorite->getFavorites();

        return $this->render('user/_favorite.html.twig', [
            'favorites' => $favorites,
        ]);
    }

    #[Route('/playlist', name: 'app_user_playlist', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function showPlaylists(
        UserInterface $user,
        UserRepository $userRepository
    ): Response {
        $userFavorite = $userRepository->findOneBy(
            ['id' => $user]
        );

        $playlists = $userFavorite->getPlaylists();

        return $this->render('user/_playlist_list.html.twig', [
            'playlists' => $playlists,
        ]);
    }

    #[Route('/playlist/{name}', name: 'app_user_playlist_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function showOnePlaylistByName(
        UserInterface $user,
        UserRepository $userRepository,
        PlaylistRepository $playlistRepository,
        string $name,
    ): Response {
        $userFavorite = $userRepository->findOneBy(
            ['id' => $user]
        );

        $playlist = $playlistRepository->findOneBy([
            'user' => $userFavorite,
            'name' => $name
        ]);

        return $this->render('user/_playlist_show.html.twig', [
            'playlist' => $playlist,
        ]);
    }
}
