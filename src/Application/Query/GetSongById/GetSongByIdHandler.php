<?php

namespace App\Application\Query\GetSongById;

use App\Application\Query\QueryHandler;
use App\Domain\Model\DomainSongModelInterface;
use App\Domain\Repository\DomainSongRepositoryInterface;

class GetSongByIdHandler extends QueryHandler
{
    public function __construct(
        private readonly DomainSongRepositoryInterface $domainSongRepository,
    ) {
    }

    public function __invoke(GetSongById $getSongById): DomainSongModelInterface
    {
        $id =  $getSongById->songId;
        return $this->domainSongRepository->find($id);
    }
}
