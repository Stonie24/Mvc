<?php
namespace App\Deck;

class Deck
{
    protected $value;

    public function __construct()
    {
        $this->value = 1;
    }

    public function assign($number): int
    {
        return $this->value = $number;
    }

    public function pop($number): array
    {
        
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getAsString(): string
    {
        return "[{$this->value}]";
    }
}