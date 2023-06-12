<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\Persistence\Repository\PlaylistRepository;
use App\Infrastructure\Persistence\Repository\SongRepository;
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
    public function showPlaylists(
        UserInterface $user,
        UserRepository $userRepository,
        SongRepository $songRepository
    ): Response {
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

        return $this->render('user/my_song.html.twig', [
            'collection' => $collection,
        ]);
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

        return $this->render('user/favorite.html.twig', [
            'favorites' => $favorites,
        ]);
    }
}
