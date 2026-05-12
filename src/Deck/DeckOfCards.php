<?php

namespace App\Deck;

class DeckOfCards
{
    /** @var DeckGraphic[] */
    private array $cards = [];

    public function __construct()
    {
        $this->initialize();
    }

    private function initialize(): void
    {
        $this->cards = [];
        foreach (Deck::getSuits() as $suit) {
            foreach (Deck::getValues() as $value) {
                $this->cards[] = new DeckGraphic($suit, $value);
            }
        }
    }

    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    public function draw(int $number = 1): array
    {
        $drawn = [];
        for ($i = 0; $i < $number; ++$i) {
            if (empty($this->cards)) {
                break;
            }
            $drawn[] = array_pop($this->cards);
        }

        return $drawn;
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function count(): int
    {
        return count($this->cards);
    }

    public function reset(): void
    {
        $this->initialize();
    }
}
