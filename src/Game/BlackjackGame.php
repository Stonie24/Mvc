<?php

namespace App\Game;

use App\Deck\DeckHand;
use App\Deck\DeckOfCards;

class BlackjackGame
{
    private DeckOfCards $deck;
    private DeckHand $playerHand;
    private DeckHand $dealerHand;
    private string $currentTurn;
    private string $gameStatus;

    public function __construct()
    {
        $this->deck = new DeckOfCards();
        $this->playerHand = new DeckHand();
        $this->dealerHand = new DeckHand();
        $this->currentTurn = 'player';
        $this->gameStatus = 'playing';
    }

    public function deal(): void
    {
        $this->deck->shuffle();

        foreach ($this->deck->draw(2) as $card) {
            $this->playerHand->add($card);
        }
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

        // räkna om ess
        while ($score > 21 && $aces > 0) {
            $score -= 10;
            --$aces;
        }

        return $score;
    }

    public function hit(): void
    {
        $card = $this->deck->draw(1)[0];
        $this->playerHand->add($card);

        if ($this->calculateScore($this->playerHand) > 21) {
            $this->gameStatus = 'player_bust';
        }
    }

    public function stand(): void
    {
        $this->currentTurn = 'dealer';
        while ($this->calculateScore($this->dealerHand) < 17) {
            $card = $this->deck->draw(1)[0];
            $this->dealerHand->add($card);
        }

        $dealerScore = $this->calculateScore($this->dealerHand);
        $playerScore = $this->calculateScore($this->playerHand);

        if ($dealerScore > 21) {
            $this->gameStatus = 'dealer_bust';
        } elseif ($playerScore <= $dealerScore) {
            $this->gameStatus = 'player_lost';
        } else {
            $this->gameStatus = 'player_win';
        }
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

    public function getPlayerHand(): DeckHand
    {
        return $this->playerHand;
    }

    public function getDealerHand(): DeckHand
    {
        return $this->dealerHand;
    }
}
