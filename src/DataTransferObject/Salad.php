<?php

namespace App\DataTransferObject;

use App\Entity\Salad as SaladEntity;

class Salad
{
    private int $id;
    private string $name;
    private string $description;
    private array $fruits;

    public function __construct()
    {
        $this->fruits = [];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getFruits(): array
    {
        return $this->fruits;
    }

    /**
     * @param $arData
     * @return void
     */
    public function fromArray($arData): void
    {
        $this->id = $arData['id'];
        $this->name = $arData['name'];
        $this->description = $arData['description'];
        foreach ($arData['fruits'] as $val) {
            $fruit = new SaladFruit();
            $fruit->fromArray($val);
            $this->fruits[] = $fruit;
        }
    }

    public function toEntity(SaladEntity $entity)
    {

        $entity
            ->setName($this->getName())
            ->setDescription(($this->getDescription()));
    }
}
