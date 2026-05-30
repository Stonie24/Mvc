<?php

namespace App\Tests\Deck;

use App\Deck\Deck;
use App\Deck\DeckGraphic;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DeckGraphic.
 */
class DeckGraphicTest extends TestCase
{
    /**
     * DeckGraphic extends Deck.
     */
    public function testExtendsBaseClass(): void
    {
        $card = new DeckGraphic('Hearts', 'A');
        $this->assertInstanceOf(Deck::class, $card);
    }

    /**
     * getAsString returns a unicode symbol, not the bracket format.
     */
    public function testGetAsStringReturnsSymbol(): void
    {
        $card = new DeckGraphic('Hearts', 'A');
        $str = $card->getAsString();
        $this->assertStringNotContainsString('[', $str);
    }

    /**
     * Red suits (Hearts, Diamonds) return color 'red'.
     */
    public function testRedSuits(): void
    {
        $hearts = new DeckGraphic('Hearts', '2');
        $diamonds = new DeckGraphic('Diamonds', '2');
        $this->assertEquals('red', $hearts->getColor());
        $this->assertEquals('red', $diamonds->getColor());
    }

    /**
     * Black suits (Spades, Clubs) return color 'black'.
     */
    public function testBlackSuits(): void
    {
        $spades = new DeckGraphic('Spades', '2');
        $clubs = new DeckGraphic('Clubs', '2');
        $this->assertEquals('black', $spades->getColor());
        $this->assertEquals('black', $clubs->getColor());
    }

    /**
     * All 52 suit/value combinations produce a non-empty symbol.
     */
    public function testAllCardsHaveSymbol(): void
    {
        foreach (Deck::getSuits() as $suit) {
            foreach (Deck::getValues() as $value) {
                $card = new DeckGraphic($suit, $value);
                $this->assertNotEmpty($card->getAsString());
            }
        }
    }
}
