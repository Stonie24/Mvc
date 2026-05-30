<?php

namespace App\Tests\Deck;

use App\Deck\Deck;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Deck.
 */
class DeckTest extends TestCase
{
    /**
     * Construct a card and verify suit and value.
     */
    public function testCreateCard(): void
    {
        $card = new Deck('Hearts', 'A');
        $this->assertEquals('Hearts', $card->getSuit());
        $this->assertEquals('A', $card->getValue());
    }

    /**
     * getAsString returns a formatted string with value and suit.
     */
    public function testGetAsString(): void
    {
        $card = new Deck('Spades', 'K');
        $this->assertEquals('[K-Spades]', $card->getAsString());
    }

    /**
     * getSuits returns an array with 4 suits.
     */
    public function testGetSuits(): void
    {
        $suits = Deck::getSuits();
        $this->assertCount(4, $suits);
        $this->assertContains('Hearts', $suits);
        $this->assertContains('Spades', $suits);
    }

    /**
     * getValues returns an array with 13 values.
     */
    public function testGetValues(): void
    {
        $values = Deck::getValues();
        $this->assertCount(13, $values);
        $this->assertContains('A', $values);
        $this->assertContains('K', $values);
    }
}
