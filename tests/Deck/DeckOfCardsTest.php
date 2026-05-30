<?php

namespace App\Tests\Deck;

use App\Deck\DeckGraphic;
use App\Deck\DeckOfCards;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DeckOfCards.
 */
class DeckOfCardsTest extends TestCase
{
    /**
     * New deck has 52 cards.
     */
    public function testNewDeckHas52Cards(): void
    {
        $deck = new DeckOfCards();
        $this->assertEquals(52, $deck->count());
    }

    /**
     * getCards returns an array of DeckGraphic objects.
     */
    public function testGetCardsReturnsDeckGraphics(): void
    {
        $deck = new DeckOfCards();
        $cards = $deck->getCards();
        $this->assertCount(52, $cards);
        $this->assertInstanceOf(DeckGraphic::class, $cards[0]);
    }

    /**
     * Draw one card reduces count by 1.
     */
    public function testDrawOneCard(): void
    {
        $deck = new DeckOfCards();
        $drawn = $deck->draw(1);
        $this->assertCount(1, $drawn);
        $this->assertEquals(51, $deck->count());
    }

    /**
     * Draw multiple cards reduces count correctly.
     */
    public function testDrawMultipleCards(): void
    {
        $deck = new DeckOfCards();
        $drawn = $deck->draw(5);
        $this->assertCount(5, $drawn);
        $this->assertEquals(47, $deck->count());
    }

    /**
     * Drawing from empty deck returns empty array without error.
     */
    public function testDrawFromEmptyDeck(): void
    {
        $deck = new DeckOfCards();
        $deck->draw(52);
        $drawn = $deck->draw(1);
        $this->assertCount(0, $drawn);
        $this->assertEquals(0, $deck->count());
    }

    /**
     * Shuffle does not change the number of cards.
     */
    public function testShuffleKeepsCount(): void
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $this->assertEquals(52, $deck->count());
    }

    /**
     * Reset restores the deck to 52 cards.
     */
    public function testReset(): void
    {
        $deck = new DeckOfCards();
        $deck->draw(10);
        $deck->reset();
        $this->assertEquals(52, $deck->count());
    }
}
