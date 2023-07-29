<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\Persistence\Entity\Song;
use App\Infrastructure\Persistence\Repository\SongRepository;
use App\Infrastructure\Service\LinkYoutubeSearch;
use App\Infrastructure\Service\SongDeezerSearch;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Route('/api/song')]
class SearchApiController extends AbstractController
{
    private SongDeezerSearch $songDeezerSearch;
    private LinkYoutubeSearch $linkYoutubeSearch;

    public function __construct(
        SongDeezerSearch $songDeezerSearch,
        LinkYoutubeSearch $linkYoutubeSearch
    ) {
        $this->songDeezerSearch = $songDeezerSearch;
        $this->linkYoutubeSearch = $linkYoutubeSearch;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     */
    #[Route('/search', name: 'api_search_song', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function searchSongs(Request $request): Response
    {
        $results = [];
        if ($request->isMethod('POST') && !empty($request->get('q'))) {
            $songTitle = $request->get('q');
            $results = $this->songDeezerSearch->search($songTitle);
            return $this->render('searchApi/index.html.twig', [
                'results' => $results,
            ]);
        }
        return $this->render('searchApi/index.html.twig', [
            'results' => $results,
        ]);
    }

    #[Route('/create', name: 'api_song_new', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function persistSongs(Request $request, SongRepository $songRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $songs = $data['songs'] ?? [];

        try {
            foreach ($songs as $songData) {
                $existingSongs = $songRepository->findBy([
                    'artist' => $songData['artist'],
                    'title' => $songData['title'],
                ]);

                if (!$existingSongs) {
                    $song = new Song();
                    $song->setTitle($songData['title']);
                    $song->setArtist($songData['artist']);
                    $song->setAlbum($songData['album']);
                    $song->setPhotoAlbum($songData['cover']);
                    $song->setLinkYoutube(
                        $this->linkYoutubeSearch->search($songData['title'], $songData['artist'])
                    );
                    $song->setIsApproved(true);
                    $songRepository->save($song, true);
                }
            }
            $this->addFlash('success', 'Sons ajoutés avec succès.');
            return $this->json(['success' => true, 'route' => $this->generateUrl('app_song_list')]);
        } catch (Exception $e) {
            $this->addFlash('danger', 'Erreur lors de l\'ajout');
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
