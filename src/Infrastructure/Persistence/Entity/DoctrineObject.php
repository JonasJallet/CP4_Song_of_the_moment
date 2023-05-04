<?php

namespace App\Infrastructure\Persistence\Entity;

use App\Domain\Model\DomainObjectModelInterface;
use App\Domain\Model\ObjectValues\IdObjectValue;

/**
* @ORM\Entity(repositoryClass="App\Infrastructure\Persistence\Repository\DoctrineObjectRepository")
 */
class DoctrineObject implements DomainObjectModelInterface,\JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private IdObjectValue $id;
    /**
     * @ORM\Column(type="varchar")
     */
    private string $field;

    public function getId(): IdObjectValue
    {
        return $this->id;
    }

    public function setId(IdObjectValue $id)
    {
       $this->id = $id;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function setField(string $field)
    {
        $this->field = $field;
    }
    public  function jsonSerialize(): array
    {
        return [
            'object' => [
                'id' => $this->id,
                'field' => $this->field
            ]
        ];
    }
}