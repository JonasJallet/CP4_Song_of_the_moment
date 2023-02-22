<?php

namespace App\Controller;

use App\Entity\Song;
use App\Form\SongType;
use App\Repository\SongRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/song')]
class SongController extends AbstractController
{
    #[Route('/', name: 'app_song_index', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request, SongRepository $songRepository): Response
    {
        if ($request->isMethod('POST')) {
            $title = $request->get('title');
            $songs = $songRepository->findLikeTitle($title);
        } else {
            $songs = $songRepository->findBy([], ['id' => 'desc']);
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
            $songs = $songRepository->findLikeTitle($title);
        } else {
            $songs = $songRepository->findBy([], ['id' => 'desc']);
        }
        return $this->render('song/list.html.twig', [
            'songs' => $songs,
        ]);
    }

    #[Route('/new', name: 'app_song_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, SongRepository $songRepository): Response
    {
        $song = new Song();
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $linkYoutube = $song->getLinkYoutube();
            $linkReplace = str_replace(['https://www.youtube.com/watch?v=', 'https://youtu.be/'], '', $linkYoutube);
            $song->setLinkYoutube($linkReplace);

            $songRepository->save($song, true);

            return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('song/new.html.twig', [
            'song' => $song,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_song_show', methods: ['GET'])]
    public function show(Song $song): Response
    {
        return $this->render('song/show.html.twig', [
            'song' => $song,
        ]);
    }

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

    #[Route('/{id}/edit', name: 'app_song_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Song $song, SongRepository $songRepository): Response
    {
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $linkYoutube = $song->getLinkYoutube();
            $linkReplace = str_replace(['https://www.youtube.com/watch?v=', 'https://youtu.be/'], '', $linkYoutube);
            $song->setLinkYoutube($linkReplace);

            $songRepository->save($song, true);

            return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('song/edit.html.twig', [
            'song' => $song,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_song_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Song $song, SongRepository $songRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $song->getId(), $request->request->get('_token'))) {
            $songRepository->remove($song, true);
        }

        return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
    }
}
