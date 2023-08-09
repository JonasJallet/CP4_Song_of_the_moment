<?php

namespace App\Infrastructure\Controller;

use App\Application\Query\User\GetFavorites\GetFavorites;
use App\Application\Query\User\GetPlaylists\GetPlaylists;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private MessageBusInterface $queryBus;

    public function __construct(
        MessageBusInterface $queryBus,
    ) {
        $this->queryBus = $queryBus;
    }

    #[Route('/my-song', name: 'app_user_song', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function showPlaylists(): Response
    {
        $userId = $this->getUser()->getId();
        $getPlaylists = new GetPlaylists($userId);
        $getPlaylists->userId = $userId;
        $result = $this->queryBus->dispatch($getPlaylists);
        $handledStamp = $result->last(HandledStamp::class);
        $collection = $handledStamp->getResult();

        return $this->render('user/my_song.html.twig', [
            'collection' => $collection,
        ]);
    }

    #[Route('/favorite', name: 'app_user_favorite', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function showFavorites(): Response
    {
        $userId = $this->getUser()->getId();
        $getFavorites = new GetFavorites($userId);
        $getFavorites->userId = $userId;
        $result = $this->queryBus->dispatch($getFavorites);
        $handledStamp = $result->last(HandledStamp::class);
        $favorites = $handledStamp->getResult();

        return $this->render('user/favorite.html.twig', [
            'favorites' => $favorites,
        ]);
    }
}
