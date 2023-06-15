<?php

namespace App\Infrastructure\Controller;

use App\Application\Command\Playlist\NewPlaylistAddSong\NewPlaylistAddSong;
use App\Application\Command\Playlist\PlaylistAddSong\PlaylistAddSong;
use App\Application\Query\Playlist\GetPlaylistById\GetPlaylistById;
use App\Infrastructure\Form\PlaylistType;
use App\Infrastructure\Persistence\Repository\PlaylistRepository;
use App\Infrastructure\Persistence\Repository\SongRepository;
use App\Infrastructure\Persistence\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/playlist')]
class PlaylistController extends AbstractController
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

    #[Route('/{playlistId}', name: 'playlist_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function showOnePlaylistById(
        string $playlistId,
    ): Response {
        $getPlaylistById = new GetPlaylistById();
        $getPlaylistById->playlistId = $playlistId;
        $result = $this->queryBus->dispatch($getPlaylistById);
        $handledStamp = $result->last(HandledStamp::class);
        $playlist = $handledStamp->getResult();

        return $this->render('user/playlist.html.twig', [
            'playlist' => $playlist,
        ]);
    }

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
    ): Response {
        $userId = $this->getUser()->getId();
        $formPlaylist = $this->createForm(PlaylistType::class)->handleRequest($request);
        $newPlaylistAddSong = new NewPlaylistAddSong();
        $newPlaylistAddSong->songId = $songId;
        $newPlaylistAddSong->userId = $userId;
        $playlist = $formPlaylist->getData();
        $newPlaylistAddSong->playlist = $playlist;
        $result = $this->commandBus->dispatch($newPlaylistAddSong);
        $handledStamp = $result->last(HandledStamp::class);
        $message = $handledStamp->getResult();

        return $this->json([
            'message' => $message
        ]);
    }

    #[Route('/popup/{songId}/', name: 'playlist_popup', methods: ['GET', 'POST'])]
    public function playlistPopup(
        int $songId,
        UserInterface $user,
        UserRepository $userRepository,
        SongRepository $songRepository,
        PlaylistRepository $playlistRepository,
    ): Response {
        $song = $songRepository->findOneBy(['id' => $songId]);
        $user = $userRepository->findOneBy(
            ['id' => $user]
        );
        $playlistsByUser = $playlistRepository->findBy(
            ['user' => $user],
            ['name' => 'ASC']
        );

        $collection = [];

        foreach ($playlistsByUser as $playlist) {
            $randomSongs = $playlistRepository->randomSongsByPlaylistId($playlist->getId());
            $collection[$playlist->getId()] = [
                'playlist' => $playlist,
                'songs' => $randomSongs,
            ];
        }

        return $this->renderForm('playlist/_playlist_popup.html.twig', [
            'collection' => $collection,
            'song' => $song,
        ]);
    }

    #[Route('/{playlistId}/add/{songId}', name: 'playlist_add_song', methods: ['GET', 'POST'])]
    public function playlistAddSong(
        int $songId,
        string $playlistId,
    ): Response
    {
        $playlistAddSong = new PlaylistAddSong();
        $playlistAddSong->songId = $songId;
        $playlistAddSong->playlistId = $playlistId;
        $result = $this->commandBus->dispatch($playlistAddSong);
        $handledStamp = $result->last(HandledStamp::class);
        $message = $handledStamp->getResult();
        return $this->json([
            'message' => $message
        ]);
    }

    #[Route('/{playlistId}/delete/{songId}', name: 'playlist_delete_song', methods: ['GET', 'POST'])]
    public function playlistRemoveSong(
        int $songId,
        string $playlistId,
        SongRepository $songRepository,
        PlaylistRepository $playlistRepository,
        SerializerInterface $serializer,
    ): Response
    {
        $song = $songRepository->findOneBy(['id' => $songId]);
        $playlist = $playlistRepository->findOneBy(['id' => $playlistId]);
        $playlist->removeSong($song);
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

    #[Route('/{playlistId}/delete', name: 'playlist_delete', methods: ['GET', 'POST'])]
    public function playlistRemove(
        string $playlistId,
        PlaylistRepository $playlistRepository,
        Request $request,
    ): Response
    {
        if ($this->isCsrfTokenValid('delete' . $playlistId, $request->request->get('_token'))) {
            $playlist = $playlistRepository->findOneBy(['id' => $playlistId]);
            $playlistRepository->remove($playlist, true);
        }
        return $this->redirectToRoute('app_user_song', [], Response::HTTP_SEE_OTHER);
    }
}
