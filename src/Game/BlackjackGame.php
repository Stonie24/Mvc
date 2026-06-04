<?php

namespace App\Game;

use App\Deck\DeckHand;
use App\Deck\DeckOfCards;

class BlackjackGame
{
    private DeckOfCards $deck;
    private array $playerHands;
    private DeckHand $dealerHand;
    private string $gameStatus;
    private int $currentHandIndex;
    private int $numHands;

    public function __construct(int $numHands = 1)
    {
        $this->deck = new DeckOfCards();
        $this->dealerHand = new DeckHand();
        $this->gameStatus = 'playing';
        $this->currentHandIndex = 0;
        $this->numHands = $numHands;

        $this->playerHands = [];
        for ($i = 0; $i < $numHands; $i++) {
            $this->playerHands[] = new DeckHand();
        }
    }

    public function deal(): void
    {
        $this->deck->shuffle();

        // Dela ut 2 kort till varje hand
        foreach ($this->playerHands as $hand) {
            foreach ($this->deck->draw(2) as $card) {
                $hand->add($card);
            }
        }

        // Dela ut 2 kort till banken
        foreach ($this->deck->draw(2) as $card) {
            $this->dealerHand->add($card);
        }
    }

    public function calculateScore(DeckHand $hand): int
    {
        $score = 0;
        $aces = 0;

        foreach ($hand->getCards() as $card) {
            $value = $card->getValue();

            if ('A' === $value) {
                ++$aces;
                $score += 11;
            } elseif (in_array($value, ['J', 'Q', 'K'])) {
                $score += 10;
            } else {
                $score += (int) $value;
            }
        }

        while ($score > 21 && $aces > 0) {
            $score -= 10;
            --$aces;
        }

        return $score;
    }

    public function hit(): void
    {
        $hand = $this->playerHands[$this->currentHandIndex];
        $card = $this->deck->draw(1)[0];
        $hand->add($card);

        if ($this->calculateScore($hand) > 21) {
            // Denna hand är bust, gå till nästa
            $this->nextHand();
        }
    }

    public function stand(): void
    {
        $this->nextHand();
    }

    private function nextHand(): void
    {
        $this->currentHandIndex++;

        // Alla händer klara, banken spelar
        if ($this->currentHandIndex >= $this->numHands) {
            $this->playDealer();
        }
    }

    private function playDealer(): void
    {
        while ($this->calculateScore($this->dealerHand) < 17) {
            $card = $this->deck->draw(1)[0];
            $this->dealerHand->add($card);
        }

        $this->gameStatus = 'done';
    }

    public function getHandStatus(int $index): string
    {
        $hand = $this->playerHands[$index];
        $playerScore = $this->calculateScore($hand);
        $dealerScore = $this->calculateScore($this->dealerHand);

        if ($playerScore > 21) {
            return 'player_bust';
        }

        if ($this->gameStatus !== 'done') {
            return 'playing';
        }

        if ($dealerScore > 21) {
            return 'dealer_bust';
        }

        if ($playerScore > $dealerScore) {
            return 'player_win';
        }

        return 'player_lost';
    }

    public function isRoundOver(): bool
    {
        return $this->gameStatus === 'done';
    }

    public function isCurrentHand(int $index): bool
    {
        return $index === $this->currentHandIndex && $this->gameStatus === 'playing';
    }

    public function getDealerVisibleScore(): int
    {
        $firstCard = $this->dealerHand->getCards()[0];
        $value = $firstCard->getValue();

        if (in_array($value, ['J', 'Q', 'K'])) {
            return 10;
        } elseif ('A' === $value) {
            return 11;
        }

        return (int) $value;
    }

    public function getGameStatus(): string
    {
        return $this->gameStatus;
    }

    public function getPlayerHands(): array
    {
        return $this->playerHands;
    }

    public function getPlayerHand(): DeckHand
    {
        return $this->playerHands[0];
    }

    public function getDealerHand(): DeckHand
    {
        return $this->dealerHand;
    }

    public function getCurrentHandIndex(): int
    {
        return $this->currentHandIndex;
    }

    public function getNumHands(): int
    {
        return $this->numHands;
    }
}