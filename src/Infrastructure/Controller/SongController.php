<?php

namespace App\Infrastructure\Controller;

use App\Application\Command\Song\AddToFavoriteSong\AddToFavoriteSong;
use App\Application\Command\Song\DeleteSong\DeleteSong;
use App\Application\Command\Song\IsApprovedSong\IsApprovedSong;
use App\Application\Command\Song\NewSong\NewSong;
use App\Application\Command\Song\UpdateSong\UpdateSong;
use App\Application\Query\Song\GetSongBySlug\GetSongBySlug;
use App\Infrastructure\Form\SongType;
use App\Infrastructure\Persistence\Entity\Song;
use App\Infrastructure\Persistence\Repository\SongRepository;
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
        return $this->render('admin/index.html.twig', [
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
        $songsApprovedList = $songRepository->findBy(['isApproved' => true], ['isApproved' => 'asc', 'id' => 'asc']);
        $searchTerm = $request->query->get('q');
        $searchSongs = $songRepository->findLikeApprovedTitle($searchTerm);

        if ($request->query->get('preview')) {
            return $this->render('song/_searchPreview.html.twig', [
                'searchSongs' => $searchSongs,
            ]);
        }

        return $this->render('song/list.html.twig', [
            'searchSongs' => $searchSongs,
            'searchTerm' => $searchTerm,
            'songsApproved' => $songsApprovedList,
        ]);
    }

    #[Route('/new', name: 'app_song_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request): Response
    {
        $form = $this->createForm(SongType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newSong = new NewSong();
            $song = $form->getData();
            $newSong->song = $song;
            $this->commandBus->dispatch($newSong);

            return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('song/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_song_show', methods: ['GET'])]
    public function show(string $slug): Response
    {
        $getSongBySlug = new GetSongBySlug();
        $getSongBySlug->slug = $slug;
        $result = $this->queryBus->dispatch($getSongBySlug);
        $handledStamp = $result->last(HandledStamp::class);
        $song = $handledStamp->getResult();

        return $this->render('song/show.html.twig', [
            'song' => $song,
        ]);
    }

    #[Route('/{songId}/isApproved', name: 'app_song_add_approved', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function addToApproved(
        string $songId,
        Request $request,
    ) {
        if ($request->isMethod('POST')) {
            $isApprovedSong = new IsApprovedSong();
            $isApprovedSong->songId = $songId;
            $result = $this->commandBus->dispatch($isApprovedSong);
            $handledStamp = $result->last(HandledStamp::class);
            $isApproved = $handledStamp->getResult();

            return $this->json([
                'isApproved' => $isApproved
            ]);
        }

        return $this->json([
            'message' => 'La requête est invalide'
        ]);
    }

    #[Route('/{songId}/favorite', name: 'app_song_add_favorite', methods: ['GET', 'POST'])]
    public function addToFavorite(string $songId): Response
    {
        $userId = $this->getUser()->getId();
        $addToFavoriteUser = new AddToFavoriteSong();
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
    public function update(string $id, Request $request, Song $song): Response
    {
        $form = $this->createForm(SongType::class, $song)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updateSong = new UpdateSong();
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
    public function delete(string $songId, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $songId, $request->request->get('_token'))) {
            $deleteSong = new DeleteSong();
            $deleteSong->songId = $songId;
            $this->commandBus->dispatch($deleteSong);
        }

        return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
    }
}
