<?php

namespace App\DataTransferObject;

class SaladFruit
{
    private ?Fruit $fruit;
    private ?float $weight;

    /**
     * @return Fruit|null
     */
    public function getFruit(): ?Fruit
    {
        return $this->fruit;
    }

    /**
     * @return float|null
     */
    public function getWeight(): ?float
    {
        return $this->weight;
    }

    /**
     * @param $arData
     * @return void
     */
    public function fromArray($arData): void
    {
        $this->fruit = new Fruit();
        $this->fruit->fromArray($arData['fruit']);
        $this->weight = $arData['weight'];
    }
}
