<?php

namespace App\Deck;

class DeckHand
{
    /** @var DeckGraphic[] */
    private array $hand = [];

    public function add(DeckGraphic $card): void
    {
        $this->hand[] = $card;
    }

    public function getNumberCards(): int
    {
        return count($this->hand);
    }

    public function getCards(): array
    {
        return $this->hand;
    }

    public function getString(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getAsString();
        }

        return $values;
    }
}
