<?php

namespace App\Service;

use App\Entity\Fruit;
use App\Entity\FruitNutrient;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class FruitGenerator
{
    protected ObjectManager $entityManager;

    public function __construct(
        ManagerRegistry $doctrine
    ) {
        $this->entityManager = $doctrine->getManager();
    }

    /**
     * @param array $fruits
     * @return void
     */
    public function init(array $fruits): void
    {
        foreach ($fruits as $fruit) {
            $objFruit = new Fruit();
            $objFruit
                ->setName($fruit['name']);
            $this->entityManager->persist($objFruit);
            foreach ($fruit['nutrients'] as $name => $weight) {
                $nutrient = new FruitNutrient();
                $nutrient
                    ->setName($name)
                    ->setWeight($weight)
                    ->setFruit($objFruit)
                ;
                $this->entityManager->persist($nutrient);
            }
        }

        $this->entityManager->flush();
    }
}
