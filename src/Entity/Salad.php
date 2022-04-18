<?php

namespace App\Entity;

use App\Repository\SaladRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SaladRepository::class)
 */
class Salad implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"list", "detail"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"list", "detail"})
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 20,
     *      minMessage = "Your salad receipt description must be at least {{ limit }} characters long",
     * )
     * @Groups({"detail"})
     */
    private string $description;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity=SaladFruit::class, mappedBy="salad", cascade={"persist"})
     * @Assert\Count(
     *      min = 2,
     *      minMessage = "You must specify at least two fruits"
     * )
     * @Groups({"detail"})
     */
    private Collection $fruits;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity=SaladNutrient::class, mappedBy="salad", orphanRemoval=true)
     * @Groups({"detail"})
     */
    private Collection $totalNutrients;

    public function __construct()
    {
        $this->fruits = new ArrayCollection([]);
        $this->totalNutrients = new ArrayCollection([]);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getTotalNutrients() : Collection
    {
        return $this->totalNutrients;
    }

    /**
     * @param SaladNutrient $nutrient
     * @return $this
     */
    public function addNutrient(SaladNutrient $nutrient): self
    {
        $this->totalNutrients[] = $nutrient;
        return $this;
    }

    /**
     * @param SaladNutrient $nutrient
     * @return $this
     */
    public function removeNutrient(SaladNutrient $nutrient): self
    {

        $this->totalNutrients->removeElement($nutrient);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getFruits() : Collection
    {
        return $this->fruits;
    }

    /**
     * @param Collection $collection
     * @return $this
     */
    public function setFruits(Collection $collection): self
    {
        $this->fruits = $collection;
        return $this;
    }

    /**
     * @param SaladFruit $fruit
     * @return $this
     */
    public function addFruit(SaladFruit $fruit): self
    {
        $this->fruits[] = $fruit;
        return $this;
    }

    /**
     * @param SaladFruit $fruit
     * @return $this
     */
    public function removeFruit(SaladFruit $fruit): self
    {
        $this->fruits->removeElement($fruit);
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
            'description' => $this->getDescription(),
            'fruits' => $this->getFruits(),
            'totalNutrients' => $this->getTotalNutrients()
        ];
    }
}
