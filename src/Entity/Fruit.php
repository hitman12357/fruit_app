<?php

namespace App\Entity;

use App\Repository\FruitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use JsonSerializable;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FruitRepository::class)
 */
class Fruit implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @var Collection
     * @OneToMany(targetEntity="FruitNutrient", mappedBy="fruit", orphanRemoval=true)
     */
    private Collection $nutrients;

    /**
     * @var Collection
     * @OneToMany(targetEntity="SaladFruit", mappedBy="fruit", orphanRemoval=true)
     */
    private Collection $saladFruits;

    /**
     * Fruit constructor
     */
    public function __construct()
    {
        $this->nutrients = new ArrayCollection([]);
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Fruit
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Fruit
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getNutrients() : Collection
    {
        return $this->nutrients;
    }

    /**
     * @param FruitNutrient $nutrient
     * @return Fruit
     */
    public function addNutrient(FruitNutrient $nutrient): self
    {
        $this->nutrients[] = $nutrient;
        return $this;
    }

    /**
     * @param FruitNutrient $nutrient
     * @return Fruit
     */
    public function removeNutrient(FruitNutrient $nutrient): self
    {
        $this->nutrients->removeElement($nutrient);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getSaladFruits(): Collection
    {
        return $this->saladFruits;
    }

    /**
     * @param SaladFruit $saladFruit
     * @return Fruit
     */
    public function addSaladFruit(SaladFruit $saladFruit): self
    {
        $this->saladFruits->add($saladFruit);
        return $this;
    }

    /**
     * @param SaladFruit $saladFruit
     * @return Fruit
     */
    public function removeSaladFruit(SaladFruit $saladFruit): self
    {
        $this->saladFruits->removeElement($saladFruit);
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'nutrients' => $this->getNutrients()
        ];
    }
}
