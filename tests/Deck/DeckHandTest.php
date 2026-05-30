<?php

namespace App\Tests\Deck;

use App\Deck\DeckGraphic;
use App\Deck\DeckHand;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DeckHand.
 */
class DeckHandTest extends TestCase
{
    /**
     * New hand has zero cards.
     */
    public function testEmptyHand(): void
    {
        $hand = new DeckHand();
        $this->assertEquals(0, $hand->getNumberCards());
    }

    /**
     * Add cards and verify count.
     */
    public function testAddCards(): void
    {
        $hand = new DeckHand();
        $hand->add(new DeckGraphic('Hearts', 'A'));
        $hand->add(new DeckGraphic('Spades', 'K'));
        $this->assertEquals(2, $hand->getNumberCards());
    }

    /**
     * getCards returns the array of DeckGraphic objects.
     */
    public function testGetCards(): void
    {
        $hand = new DeckHand();
        $card = new DeckGraphic('Clubs', '7');
        $hand->add($card);

        $cards = $hand->getCards();
        $this->assertCount(1, $cards);
        $this->assertInstanceOf(DeckGraphic::class, $cards[0]);
    }

    /**
     * getString returns an array of string representations.
     */
    public function testGetString(): void
    {
        $hand = new DeckHand();
        $hand->add(new DeckGraphic('Hearts', 'A'));
        $hand->add(new DeckGraphic('Diamonds', '10'));

        $strings = $hand->getString();
        $this->assertCount(2, $strings);
        $this->assertIsString($strings[0]);
    }
}
