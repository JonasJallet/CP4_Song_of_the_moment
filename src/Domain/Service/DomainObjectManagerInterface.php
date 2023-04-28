<?php

namespace App\Domain\Service\Manager;

interface DomainObjectManagerInterface
{

    public function update($order, array $fields);
}