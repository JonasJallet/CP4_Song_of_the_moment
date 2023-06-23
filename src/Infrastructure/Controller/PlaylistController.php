<?php

namespace App\Infrastructure\Controller;

use App\Application\Command\Playlist\AddSongNewPlaylist\AddSongNewPlaylist;
use App\Application\Command\Playlist\AddSongPlaylist\AddSongPlaylist;
use App\Application\Command\Playlist\DeletePlaylist\DeletePlaylist;
use App\Application\Command\Playlist\DeleteSongPlaylist\DeleteSongPlaylist;
use App\Application\Query\Playlist\GetPlaylistBySlug\GetPlaylistBySlug;
use App\Application\Query\User\GetPlaylists\GetPlaylists;
use App\Infrastructure\Form\PlaylistType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/{slug}', name: 'playlist_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function showOnePlaylistById(
        string $slug,
    ): Response {
        $getPlaylistBySlug = new GetPlaylistBySlug();
        $getPlaylistBySlug->slug = $slug;
        $result = $this->queryBus->dispatch($getPlaylistBySlug);
        $handledStamp = $result->last(HandledStamp::class);
        $playlist = $handledStamp->getResult();

        return $this->render('user/playlist.html.twig', [
            'playlist' => $playlist,
        ]);
    }

    #[Route('/new/{songId}', name: 'playlist_popup_new', methods: ['GET', 'POST'])]
    public function playlistPopupNew(
        string $songId,
        Request $request,
    ): Response {
        $createPlaylistForm = $this->createForm(PlaylistType::class)->handleRequest($request);

        return $this->renderForm('playlist/_playlist_new_popup.html.twig', [
            'songId' => $songId,
            'createPlaylistForm' => $createPlaylistForm,
        ]);
    }

    #[Route('/new/add/{songId}', name: 'playlist_new_add', methods: ['GET', 'POST'])]
    public function playlistNewAdd(
        string $songId,
        Request $request,
    ): Response {
        $userId = $this->getUser()->getId();
        $formPlaylist = $this->createForm(PlaylistType::class)->handleRequest($request);
        $newPlaylistAddSong = new AddSongNewPlaylist();
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
        string $songId,
    ): Response {
        $userId = $this->getUser()->getId();
        $getPlaylists = new GetPlaylists();
        $getPlaylists->userId = $userId;
        $result = $this->queryBus->dispatch($getPlaylists);
        $handledStamp = $result->last(HandledStamp::class);
        $collection = $handledStamp->getResult();

        return $this->renderForm('playlist/_playlist_popup.html.twig', [
            'collection' => $collection,
            'songId' => $songId,
        ]);
    }

    #[Route('/{playlistId}/add/{songId}', name: 'playlist_add_song', methods: ['GET', 'POST'])]
    public function playlistAddSong(
        string $songId,
        string $playlistId,
    ): Response
    {
        $playlistAddSong = new AddSongPlaylist();
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
        string $songId,
        string $playlistId,
    ): Response
    {
        $playlistDeleteSong = new DeleteSongPlaylist();
        $playlistDeleteSong->songId = $songId;
        $playlistDeleteSong->playlistId = $playlistId;
        $result = $this->commandBus->dispatch($playlistDeleteSong);
        $handledStamp = $result->last(HandledStamp::class);
        $delete = $handledStamp->getResult();

        return $this->json([
            'delete' => $delete
        ]);
    }

    #[Route('/{playlistId}/delete', name: 'playlist_delete', methods: ['GET', 'POST'])]
    public function playlistRemove(
        string $playlistId,
        Request $request,
    ): Response
    {
        if ($this->isCsrfTokenValid('delete' . $playlistId, $request->request->get('_token'))) {
            $deletePlaylist = new DeletePlaylist();
            $deletePlaylist->playlistId = $playlistId;
            $this->commandBus->dispatch($deletePlaylist);
        }
        return $this->redirectToRoute('app_user_song', [], Response::HTTP_SEE_OTHER);
    }
}
