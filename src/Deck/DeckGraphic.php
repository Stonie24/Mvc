<?php

namespace App\Deck;

class DeckGraphic extends Deck
{
    private const SYMBOLS = [
        'Hearts' => ['2' => '🂲', '3' => '🂳', '4' => '🂴', '5' => '🂵', '6' => '🂶',
            '7' => '🂷', '8' => '🂸', '9' => '🂹', '10' => '🂺', 'J' => '🂻',
            'Q' => '🂽', 'K' => '🂾', 'A' => '🂱'],
        'Diamonds' => ['2' => '🃂', '3' => '🃃', '4' => '🃄', '5' => '🃅', '6' => '🃆',
            '7' => '🃇', '8' => '🃈', '9' => '🃉', '10' => '🃊', 'J' => '🃋',
            'Q' => '🃍', 'K' => '🃎', 'A' => '🃁'],
        'Spades' => ['2' => '🂢', '3' => '🂣', '4' => '🂤', '5' => '🂥', '6' => '🂦',
            '7' => '🂧', '8' => '🂨', '9' => '🂩', '10' => '🂪', 'J' => '🂫',
            'Q' => '🂭', 'K' => '🂮', 'A' => '🂡'],
        'Clubs' => ['2' => '🃒', '3' => '🃓', '4' => '🃔', '5' => '🃕', '6' => '🃖',
            '7' => '🃗', '8' => '🃘', '9' => '🃙', '10' => '🃚', 'J' => '🃛',
            'Q' => '🃝', 'K' => '🃞', 'A' => '🃑'],
    ];

    public function __construct(string $suit, string $value)
    {
        parent::__construct($suit, $value);
    }

    public function getAsString(): string
    {
        return self::SYMBOLS[$this->suit][$this->value];
    }

    public function getColor(): string
    {
        return in_array($this->suit, ['Hearts', 'Diamonds']) ? 'red' : 'black';
    }
}
