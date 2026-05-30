<?php

namespace App\Deck;

interface CardInterface
{
    public function getValue(): string;
    public function getSuit(): string;
    public function getAsString(): string;
}
