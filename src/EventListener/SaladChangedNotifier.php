<?php

namespace App\EventListener;

use App\Entity\FruitNutrient;
use App\Entity\Salad;
use App\Entity\SaladFruit;
use App\Entity\SaladNutrient;
use App\Repository\FruitRepository;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class SaladChangedNotifier
{
    private FruitRepository $fruitRepository;
    private ObjectManager $entityManager;

    public function __construct(
        FruitRepository $fruitRepository,
        ManagerRegistry $doctrine
    ) {
        $this->entityManager = $doctrine->getManager();
        $this->fruitRepository = $fruitRepository;
    }

    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function postPersist(Salad $salad, LifecycleEventArgs $event): void
    {
        $nutrients = [];
        /** @var SaladFruit $val */
        foreach ($salad->getFruits() as $val) {
            $fruit = $this->fruitRepository->findOneBy(['id' => $val->getFruit()]);
            if(is_null($fruit)) {
                continue;
            }
            /** @var FruitNutrient $nutrient */
            foreach ($fruit->getNutrients() as $nutrient) {
                if(!array_key_exists($nutrient->getName(), $nutrients)) {
                    $nutrients[$nutrient->getName()] = 0;
                }
                $nutrients[$nutrient->getName()] += $nutrient->getWeight();
            }
        }
        foreach ($nutrients as $nutrientName => $nutrientWeight) {
            $saladNutrient = new SaladNutrient();
            $saladNutrient
                ->setName($nutrientName)
                ->setWeight($nutrientWeight)
                ->setSalad($salad)
            ;
            $this->entityManager->persist($saladNutrient);
        }
        $this->entityManager->flush();
    }
}
