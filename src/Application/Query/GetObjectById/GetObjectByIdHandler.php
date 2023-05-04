<?php

namespace App\Application\Query\Orders\GetOrderById;

use App\Application\Query\GetObjectById\GetObjectById;
use App\Application\Query\QueryHandler;
use App\Domain\Exception\DomainObjectNotFoundException;
use App\Domain\Model\DomainObjectModelInterface;
use App\Domain\Model\ObjectValues\IdObjectValue;
use App\Domain\Repository\DomainObjectRepositoryInterface;


class GetObjectByIdHandler extends QueryHandler
{
    public function __construct(
        private readonly DomainObjectRepositoryInterface $domainObjectRepository,
    ) {}

    public function __invoke(GetObjectById $getObjectById): DomainObjectModelInterface
    {
        $id =  IdObjectValue::fromInt($getObjectById->objectId);
        $object = $this->domainObjectRepository->find($id->incrementNumber);

        self::ensureObjectExist($object->getId());

        return $object;
    }
    private function ensureObjectExist(IdObjectValue $id): void
    {
        if (!$this->domainObjectRepository->existById($id->incrementNumber))
            throw new DomainObjectNotFoundException();
    }
}