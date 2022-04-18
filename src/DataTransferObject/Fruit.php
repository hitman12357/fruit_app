<?php

namespace App\DataTransferObject;

class Fruit
{
    private int $id;

    public function getId(): int
    {
        return $this->id;
    }

    public function fromArray($arData)
    {
        if(array_key_exists('id', $arData)) {
            $this->id = $arData['id'];
        }
    }
}
