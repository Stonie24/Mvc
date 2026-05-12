<?php

namespace App\Controller;

use App\Deck\DeckOfCards;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerJson
{
    #[Route('/api')]
    public function jsonRoutes(): Response
    {
        $data = [
            'routes' => [
                ['method' => 'GET',  'path' => '/api/quote',              'description' => 'Returns a random quote with date and timestamp'],
                ['method' => 'GET',  'path' => '/api/deck',               'description' => 'Returns the full sorted deck as JSON'],
                ['method' => 'POST', 'path' => '/api/deck/shuffle',       'description' => 'Shuffles the deck and returns it as JSON'],
                ['method' => 'POST', 'path' => '/api/deck/draw',          'description' => 'Draws one card from the deck'],
                ['method' => 'POST', 'path' => '/api/deck/draw/{number}', 'description' => 'Draws {number} cards from the deck'],
            ],
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route('/api/quote')]
    public function jsonQuote(): Response
    {
        $quotes = [
            'Det var en gång en gång och den var sandad',
            'Här kommer göran göran ade göran',
            'här har en hare hoppat hare? a det hare',
        ];

        $data = [
            'quote' => $quotes[random_int(0, 2)],
            'date' => date('Y-m-d'),
            'timestamp' => time(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route('/api/deck', name: 'api_get_deck', methods: ['GET'])]
    public function getDeck(): Response
    {
        $deck = new DeckOfCards();

        $cards = array_map(
            fn ($card) => $card->getAsString(),
            $deck->getCards()
        );

        $data = [
            'cards' => $cards,
            'remaining' => $deck->count(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route('/api/deck/shuffle', name: 'api_shuffle_deck', methods: ['POST'])]
    public function shuffleDeck(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set('deck', $deck);

        $cards = array_map(
            fn ($card) => $card->getAsString(),
            $deck->getCards()
        );

        $data = [
            'cards' => $cards,
            'remaining' => $deck->count(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route('/api/deck/draw', name: 'api_deck_draw', methods: ['POST'])]
    public function drawOne(SessionInterface $session): Response
    {
        $deck = $session->get('deck', new DeckOfCards());
        $drawn = $deck->draw(1);
        $session->set('deck', $deck);

        $data = [
            'drawn' => array_map(fn ($card) => $card->getAsString(), $drawn),
            'remaining' => $deck->count(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route('/api/deck/draw/{number<\d+>}', name: 'api_deck_draw_num', methods: ['POST'])]
    public function drawMany(int $number, SessionInterface $session): Response
    {
        $deck = $session->get('deck', new DeckOfCards());

        if ($number > $deck->count()) {
            $data = ['error' => 'Not enough cards left in the deck!'];
        } else {
            $drawn = $deck->draw($number);
            $session->set('deck', $deck);

            $data = [
                'drawn' => array_map(fn ($card) => $card->getAsString(), $drawn),
                'remaining' => $deck->count(),
            ];
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
