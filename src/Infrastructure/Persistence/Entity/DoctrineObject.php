<?php

namespace App\Infrastructure\Persistence\Entity;

use App\Domain\Model\DomainObjectModelInterface;
use App\Domain\Model\ObjectValues\IdObjectValue;

/**
* @ORM\Entity(repositoryClass="App\Infrastructure\Persistence\Repository\DoctrineObjectRepository")
 */
class DoctrineObject implements DomainObjectModelInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private IdObjectValue $id;

    public function getId(): IdObjectValue
    {
        return $this->id;
    }

    public function setId(IdObjectValue $id)
    {
       $this->id = $id;
    }
}