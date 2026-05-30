<?php

namespace App\Deck;

class Deck implements CardInterface
{
    protected string $suit;
    protected string $value;

    private const SUITS = ['Hearts', 'Diamonds', 'Clubs', 'Spades'];
    private const VALUES = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];

    public function __construct(string $suit, string $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getAsString(): string
    {
        return "[{$this->value}-{$this->suit}]";
    }

    public static function getSuits(): array
    {
        return self::SUITS;
    }

    public static function getValues(): array
    {
        return self::VALUES;
    }
}
