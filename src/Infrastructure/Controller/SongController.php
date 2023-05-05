<?php

namespace App\Infrastructure\Controller;

use App\Application\Query\GetSongById\GetSongById;
use App\Form\SongType;
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
    public function __construct(
        public MessageBusInterface $queryBus,
    ) {
    }

//    #[Route('/', name: 'app_song_index', methods: ['GET', 'POST'])]
//    #[IsGranted('ROLE_ADMIN')]
//    public function index(Request $request): Response
//    {
//
//
//        if ($request->isMethod('POST')) {
//            $getSongs = new GetSongsByTitle();
//            $songs = $this->queryBus->dispatch($getSongs);
//        } else {
//            $songs = $songRepository->findBy([], ['id' => 'desc']);
//        }
//
//        return $this->render('song/index.html.twig', [
//            'songs' => $songs,
//        ]);
//    }

//    #[Route('/random', name: 'app_song_random', methods: ['GET'])]
//    public function random(SongRepository $songRepository): Response
//    {
//        return $this->render('song/random.html.twig', [
//            'songs' => $songRepository->randomSong(),
//        ]);
//    }

//    #[Route('/list', name: 'app_song_list', methods: ['GET', 'POST'])]
//    public function list(Request $request, SongRepository $songRepository): Response
//    {
//        if ($request->isMethod('POST')) {
//            $title = $request->get('title');
//            $songs = $songRepository->findLikeApprovedTitle($title);
//        } else {
//            $songs = $songRepository->allApprovedSong();
//        }
//        return $this->render('song/list.html.twig', [
//            'songs' => $songs,
//        ]);
//    }
//
//    #[Route('/new', name: 'app_song_new', methods: ['GET', 'POST'])]
//    #[IsGranted('ROLE_ADMIN')]
//    public function new(Request $request, SongRepository $songRepository): Response
//    {
//        $song = new Song();
//        $form = $this->createForm(SongType::class, $song)->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->songManager->formatLinkYoutube($song);
//            $songRepository->save($song, true);
//
//            return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//
//        return $this->renderForm('song/new.html.twig', [
//            'song' => $song,
//            'form' => $form,
//        ]);
//    }

    #[Route('/{songId}', name: 'app_song_show', methods: ['GET'])]
    public function show(int $songId): Response
    {
        $getSong = new GetSongById();
        $getSong->songId = $songId;
        $result = $this->queryBus->dispatch($getSong);

        $handledStamp = $result->last(HandledStamp::class);
        $song = $handledStamp->getResult();

        return $this->render('song/show.html.twig', [
            'song' => $song,
        ]);
    }

//    #[Route('/{id}/isApproved', name: 'app_song_add_approved', methods: ['GET', 'POST'])]
//    public function addToApproved(
//        Song $song,
//        SongRepository $songRepository,
//        Request $request
//    ): Response {
//        $songs = $songRepository->allApprovedSong();
//
//        if ($request->isMethod('POST')) {
//            $id = $request->get('id');
//            $song = $songRepository->findOneBy(['id' => $id]);
//            $song->setIsApproved(true);
//            $songRepository->save($song, true);
//
//            return $this->json(data: [
//                'isApproved' => $song
//            ]);
//        }
//        return $this->render('song/list.html.twig', [
//            'songs' => $songs,
//        ]);
//    }
//
    #[Route('/{id}/favorite', name: 'app_song_add_favorite', methods: ['GET', 'POST'])]
    public function addToFavorite(
        Song $song,
        UserRepository $userRepository
    ): Response {
        $user = $this->getUser();
        $user = $userRepository->findOneBy(
            ['id' => $user]
        );

        if ($user->isInFavorite($song)) {
            $user->removeFavorite($song);
        } else {
            $user->addFavorite($song);
        }
        $userRepository->save($user, true);

        $isInFavorite = $user->isInFavorite($song);

        return $this->json([
            'isInFavorite' => $isInFavorite
        ]);
    }
//
//    #[Route('/{id}/edit', name: 'app_song_edit', methods: ['GET', 'POST'])]
//    #[IsGranted('ROLE_ADMIN')]
//    public function edit(Request $request, Song $song, SongRepository $songRepository): Response
//    {
//        $form = $this->createForm(SongType::class, $song)->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->songManager->formatLinkYoutube($song);
//            $songRepository->save($song, true);
//
//            return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('song/edit.html.twig', [
//            'song' => $song,
//            'form' => $form,
//        ]);
//    }
//
//    #[Route('/{id}', name: 'app_song_delete', methods: ['POST'])]
//    #[IsGranted('ROLE_ADMIN')]
//    public function delete(Request $request, Song $song, SongRepository $songRepository): Response
//    {
//        if ($this->isCsrfTokenValid('delete' . $song->getId(), $request->request->get('_token'))) {
//            $songRepository->remove($song, true);
//        }
//
//        return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
//    }

}
