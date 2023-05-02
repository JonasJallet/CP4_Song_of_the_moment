<?php

namespace App\Application\Command\UpdateDomainObject;

use App\Application\Command\CommandHandler;
use App\Domain\Exception\DomainObjectNotFoundException;
use App\Domain\Model\ObjectValues\IdObjectValue;
use App\Domain\Repository\DomainObjectRepositoryInterface;
use App\Domain\Service\Manager\DomainObjectManagerInterface;

class UpdateDomainObjectHandler extends CommandHandler
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
        $id = IdObjectValue::fromInt($id);
        self::ensureObjectExist($id);

        $object = $this->domainObjectRepository->find($id->incrementNumber);
        $this->domainObjectManager->update($object, $fields);
    }

    private function ensureObjectExist(IdObjectValue $id): void
    {
        if (!$this->domainObjectRepository->existById($id->incrementNumber))
            throw new DomainObjectNotFoundException();
    }


}