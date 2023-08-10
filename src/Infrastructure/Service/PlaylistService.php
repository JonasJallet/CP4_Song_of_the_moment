<?php

namespace App\Infrastructure\Service;

use App\Domain\Model\DomainPlaylistModelInterface;
use App\Domain\Model\DomainSongPlaylistModelInterface;
use App\Domain\Repository\DomainPlaylistRepositoryInterface;
use App\Domain\Service\PlaylistServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PlaylistService implements PlaylistServiceInterface
{
    private DomainPlaylistRepositoryInterface $playlistRepository;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(
        DomainPlaylistRepositoryInterface $playlistRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->playlistRepository = $playlistRepository;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function addSong(
        DomainPlaylistModelInterface $playlist,
        DomainSongPlaylistModelInterface $songPlaylist
    ): JsonResponse {
        $playlist->addSong($songPlaylist);
        $errors = $this->validator->validate($playlist);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse([
                'errors' => $errorMessages
            ], 400);
        }

        $this->playlistRepository->save($playlist, true);
        $serializedPlaylist = $this->serializer->serialize(
            $playlist,
            'json',
            ['groups' => ['default'], 'enable_max_depth' => true]
        );

        return new JsonResponse([
            'playlist' => $serializedPlaylist
        ]);
    }
}
