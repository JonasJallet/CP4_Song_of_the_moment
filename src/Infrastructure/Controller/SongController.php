<?php

namespace App\Infrastructure\Controller;

use App\Application\Command\AddToFavoriteUser\AddToFavoriteUser;
use App\Application\Command\DeleteDomainSong\DeleteDomainSong;
use App\Application\Command\NewDomainSong\NewDomainSong;
use App\Application\Command\UpdateDomainSong\UpdateDomainSong;
use App\Application\Query\GetAllApprovedSongs\GetAllApprovedSongs;
use App\Application\Query\GetSongById\GetSongById;
use App\Infrastructure\Form\SongType;
use App\Infrastructure\Manager\SongManager;
use App\Infrastructure\Persistence\Entity\Song;
use App\Infrastructure\Persistence\Repository\SongRepository;
use App\Infrastructure\Persistence\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/song')]
class SongController extends AbstractController
{
    private MessageBusInterface $queryBus;
    private MessageBusInterface $commandBus;

    public function __construct(
        MessageBusInterface $queryBus,
        MessageBusInterface $commandBus,
    ) {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
    }

    #[Route('/', name: 'app_song_index', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request, SongRepository $songRepository): Response
    {
        if ($request->isMethod('POST') && !empty($request->get('title'))) {
            $title = $request->get('title');
            $songs = $songRepository->findLikeTitle($title);
        } elseif ($request->isMethod('POST') && !empty($request->get('isApproved'))) {
            $isApproved = $request->get('isApproved');
            $songs = $songRepository->findLikeApproved($isApproved);
        } else {
            $songs = $songRepository->findBy([], ['isApproved' => 'asc', 'id' => 'asc']);
        }
        return $this->render('song/index.html.twig', [
            'songs' => $songs,
        ]);
    }

    #[Route('/random', name: 'app_song_random', methods: ['GET'])]
    public function random(SongRepository $songRepository): Response
    {
        return $this->render('song/random.html.twig', [
            'songs' => $songRepository->randomSong(),
        ]);
    }

    #[Route('/list', name: 'app_song_list', methods: ['GET', 'POST'])]
    public function list(Request $request, SongRepository $songRepository): Response
    {
        if ($request->isMethod('POST')) {
            $title = $request->get('title');
            $songs = $songRepository->findLikeApprovedTitle($title);
        } else {
            $getSongs = new GetAllApprovedSongs();
            $result = $this->queryBus->dispatch($getSongs);

            $handledStamp = $result->last(HandledStamp::class);
            $songs = $handledStamp->getResult();
        }
        return $this->render('song/list.html.twig', [
            'songs' => $songs,
        ]);
    }

    #[Route('/new', name: 'app_song_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request): Response
    {
        $song = new Song();
        $form = $this->createForm(SongType::class, $song)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newSong = new NewDomainSong();
            $newSong->song = $song;
            $this->commandBus->dispatch($newSong);

            return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('song/new.html.twig', [
            'song' => $song,
            'form' => $form,
        ]);
    }

    #[Route('/{songId}', name: 'app_song_show', methods: ['GET'])]
    public function show(int $songId): Response
    {
        $getSongById = new GetSongById();
        $getSongById->songId = $songId;
        $result = $this->queryBus->dispatch($getSongById);

        $handledStamp = $result->last(HandledStamp::class);
        $song = $handledStamp->getResult();

        return $this->render('song/show.html.twig', [
            'song' => $song,
        ]);
    }

    #[Route('/{id}/isApproved', name: 'app_song_add_approved', methods: ['GET', 'POST'])]
    public function addToApproved(
        Song $song,
        SongRepository $songRepository,
        Request $request
    ): Response {

        if ($request->isMethod('POST')) {
            $id = $request->get('id');
            $song = $songRepository->findOneBy(['id' => $id]);
            $song->setIsApproved(true);
            $songRepository->save($song, true);

            return $this->json(data: [
                'isApproved' => $song
            ]);
        }
        return $this->render('song/list.html.twig', [
            'song' => $song,
        ]);
    }

    #[Route('/{songId}/favorite', name: 'app_song_add_favorite', methods: ['GET', 'POST'])]
    public function addToFavorite(int $songId): Response
    {
        $userId = $this->getUser()->getId();
        $addToFavoriteUser = new AddToFavoriteUser();
        $addToFavoriteUser->userId = $userId;
        $addToFavoriteUser->songId = $songId;
        $result = $this->commandBus->dispatch($addToFavoriteUser);

        $handledStamp = $result->last(HandledStamp::class);
        $isInFavorite = $handledStamp->getResult();

        return $this->json([
            'isInFavorite' => $isInFavorite
        ]);
    }

    #[Route('/{id}/update', name: 'app_song_update', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(int $id, Request $request, Song $song): Response
    {
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updateSong = new UpdateDomainSong();
            $updateSong->id = $id;
            $this->commandBus->dispatch($updateSong);

            return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('song/update.html.twig', [
            'song' => $song,
            'form' => $form,
        ]);
    }

    #[Route('/{songId}/delete', name: 'app_song_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(int $songId, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $songId, $request->request->get('_token'))) {
            $deleteSong = new DeleteDomainSong();
            $deleteSong->songId = $songId;
            $this->commandBus->dispatch($deleteSong);
        }

        return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
    }
}
