<?php

namespace App\Entity;

use App\Repository\SaladFruitRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use JsonSerializable;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SaladFruitRepository::class)
 */
class SaladFruit implements JsonSerializable
{
    /**
     * @var Fruit
     * @Id
     * @ORM\ManyToOne(targetEntity=Fruit::class, inversedBy="saladFruits")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"detail"})
     */
    private Fruit $fruit;

    /**
     * @var float
     * @ORM\Column(type="float")
     * @Groups({"detail"})
     */
    private float $weight;

    /**
     * @var Salad
     * @Id
     * @ORM\ManyToOne(targetEntity=Salad::class, inversedBy="fruits")
     * @ORM\JoinColumn(nullable=false)
     */
    private Salad $salad;

    /**
     * @return Fruit
     */
    public function getFruit(): Fruit
    {
        return $this->fruit;
    }

    /**
     * @param Fruit $fruit
     * @return SaladFruit
     */
    public function setFruit(Fruit $fruit): SaladFruit
    {
        $this->fruit = $fruit;
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
     * @return SaladFruit
     */
    public function setWeight(float $weight): SaladFruit
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return Salad
     */
    public function getSalad(): Salad
    {
        return $this->salad;
    }

    /**
     * @param Salad $salad
     * @return SaladFruit
     */
    public function setSalad(Salad $salad): SaladFruit
    {
        $this->salad = $salad;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'fruit' => $this->getFruit(),
            'weight' => $this->getWeight()
        ];
    }
}
