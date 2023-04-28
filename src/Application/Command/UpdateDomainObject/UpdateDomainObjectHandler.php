<?php

namespace App\Application\Command\UpdateDomainObject;

use App\Application\Command\UpdateDomainObject\UpdateDomainObject;
use App\Domain\Exception\DomainObjectNotFoundException;
use App\Domain\Repository\DomainObjectRepositoryInterface;
use App\Domain\Service\Manager\DomainObjectManagerInterface;
use MongoDB\BSON\ObjectId;

class UpdateDomainObjectHandler
{

    public function __construct
    (
        private readonly DomainObjectRepositoryInterface $domainObjectRepository,
        private readonly DomainObjectManagerInterface    $domainObjectManager,
    )
    {
    }

    public function __invoke(UpdateDomainObject $updateDomainObject): void
    {
        $id = $updateDomainObject->id;
        $fields = $updateDomainObject->fields;

        self::ensureObjectExist($id);

        $order = $this->domainObjectRepository->find($id);
        $this->domainObjectManager->update($order, $fields);
    }

    private function ensureObjectExist(ObjectId $id): void
    {
        if (!$this->domainObjectRepository->existById($id))
            throw new DomainObjectNotFoundException();
    }


}