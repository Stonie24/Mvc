<?php
namespace App\Deck;

class DeckGraphic extends Deck
{
    private $representation = [
        '🂱',
        '🂲',
        '🂳',
        '🂴',
        '🂵',
        '🂶',
        '🂷',
        '🂸',
        '🂹',
        '🂺',
        '🂻',
        '🂽',
        '🂾',
        '🃁',
        '🃂',
        '🃃',
        '🃄',
        '🃅',
        '🃆',
        '🃇',
        '🃈',
        '🃉',
        '🃊',
        '🃋',
        '🃍',
        '🃎',
        '🂡',
        '🂢',
        '🂣',
        '🂤',
        '🂥',
        '🂦',
        '🂧',
        '🂨',
        '🂩',
        '🂪',
        '🂫',
        '🂭',
        '🂮',
        '🃑',
        '🃒',
        '🃓',
        '🃔',
        '🃕',
        '🃖',
        '🃗',
        '🃘',
        '🃙',
        '🃚',
        '🃛',
        '🃝',
        '🃞',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function pop($number): array
    {
        $index = array_search($number, $this->representation);

        if ($index !== false) {
            array_splice($this->representation, $index, 1);
        }
        
        return $this->representation;
    }

    public function getAsString(): string
    {
        return $this->representation[$this->value - 1];
    }
}
