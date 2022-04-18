<?php

namespace App\Entity;

use App\Repository\SaladNutrientRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SaladNutrientRepository::class)
 */
class SaladNutrient implements JsonSerializable
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
     * @Groups({"list", "detail"})
     */
    private string $name;

    /**
     * @var float
     * @ORM\Column(type="float")
     * @Groups({"list", "detail"})
     */
    private float $weight;

    /**
     * @var Salad
     * @ORM\ManyToOne(targetEntity=Salad::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private Salad $salad;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return SaladNutrient
     */
    public function setId(int $id): SaladNutrient
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
     * @return SaladNutrient
     */
    public function setName(string $name): SaladNutrient
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
     * @return SaladNutrient
     */
    public function setWeight(float $weight): SaladNutrient
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
     * @return SaladNutrient
     */
    public function setSalad(Salad $salad): SaladNutrient
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
            'name' => $this->getName(),
            'weight' => $this->getWeight()
        ];
    }

}
