<?php

namespace App\Infrastructure\Controller;

use App\Application\Command\SearchApi\CreateSong\CreateSong;
use App\Infrastructure\Persistence\Entity\Song;
use App\Infrastructure\Persistence\Repository\SongRepository;
use App\Infrastructure\Service\LinkYoutubeSearch;
use App\Infrastructure\Service\SongDeezerSearch;
use App\Infrastructure\Service\SongUploadCover;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
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
    private MessageBusInterface $queryBus;
    private MessageBusInterface $commandBus;

    public function __construct(
        SongDeezerSearch $songDeezerSearch,
        LinkYoutubeSearch $linkYoutubeSearch,
        SongUploadCover $songUploadCover,
        MessageBusInterface $queryBus,
        MessageBusInterface $commandBus
    ) {
        $this->songDeezerSearch = $songDeezerSearch;
        $this->linkYoutubeSearch = $linkYoutubeSearch;
        $this->songUploadCover = $songUploadCover;
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
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

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/create', name: 'api_song_new', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function persistSongs(
        Request $request,
        SongRepository $songRepository,
    ): Response {
        $data = json_decode($request->getContent(), true);
        $songsData = $data['songs'] ?? [];

        try {
            $command = new CreateSong($songsData);
            $this->commandBus->dispatch($command);
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
