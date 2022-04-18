<?php

namespace App\Entity;

use App\Repository\FruitNutrientRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=FruitNutrientRepository::class)
 */
class FruitNutrient implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private float $weight;

    /**
     * @var Fruit
     * @ORM\ManyToOne(targetEntity=Fruit::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private Fruit $fruit;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return FruitNutrient
     */
    public function setId(int $id): FruitNutrient
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return FruitNutrient
     */
    public function setName(string $name): FruitNutrient
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     * @return FruitNutrient
     */
    public function setWeight(float $weight): FruitNutrient
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return Fruit
     */
    public function getFruit(): Fruit
    {
        return $this->fruit;
    }

    /**
     * @param Fruit $fruit
     * @return FruitNutrient
     */
    public function setFruit(Fruit $fruit): FruitNutrient
    {
        $this->fruit = $fruit;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'weight' => $this->getWeight()
        ];
    }
}
