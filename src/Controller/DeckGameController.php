<?php

namespace App\Controller;

use App\Deck\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DeckGameController extends AbstractController
{
    #[Route('/card', name: 'card_start')]
    public function home(): Response
    {
        return $this->render('deck/home.html.twig');
    }

    #[Route('/session', name: 'session_show')]
    public function showSession(SessionInterface $session): Response
    {
        $data = [
            'session' => $session->all(),
        ];

        return $this->render('deck/session.html.twig', $data);
    }

    #[Route('/session/delete', name: 'session_delete')]
    public function deleteSession(SessionInterface $session): Response
    {
        $session->clear();

        $this->addFlash('notice', 'Session has been cleared!');

        return $this->redirectToRoute('session_show');
    }

    #[Route('/card/deck', name: 'card_deck')]
    public function displayDeck(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $session->set('deck', $deck);

        $cards = [];
        foreach ($deck->getCards() as $card) {
            $cards[] = [
                'symbol' => $card->getAsString(),
                'color' => $card->getColor(),
                'suit' => $card->getSuit(),
            ];
        }

        $data = [
            'cards' => $cards,
            'num_remaining' => $deck->count(),
        ];

        return $this->render('deck/deck.html.twig', $data);
    }

    #[Route('/card/deck/shuffle', name: 'deck_shuffle')]
    public function shuffleDeck(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set('deck', $deck);

        $cards = [];
        foreach ($deck->getCards() as $card) {
            $cards[] = [
                'symbol' => $card->getAsString(),
                'color' => $card->getColor(),
            ];
        }

        $data = [
            'cards' => $cards,
            'num_remaining' => $deck->count(),
        ];

        return $this->render('deck/deck.html.twig', $data);
    }

    #[Route('/card/deck/draw', name: 'deck_draw')]
    public function drawOne(SessionInterface $session): Response
    {
        $deck = $session->get('deck', new DeckOfCards());

        $drawn = $deck->draw(1);
        $session->set('deck', $deck);

        $cards = [];
        foreach ($drawn as $card) {
            $cards[] = [
                'symbol' => $card->getAsString(),
                'color' => $card->getColor(),
            ];
        }

        $data = [
            'cards' => $cards,
            'num_remaining' => $deck->count(),
        ];

        return $this->render('deck/draw.html.twig', $data);
    }

    #[Route('/card/deck/draw/{num<\d+>}', name: 'deck_draw_num')]
    public function drawMany(int $num, SessionInterface $session): Response
    {
        if ($num > 52) {
            throw new \Exception('Can not draw more than 52 cards!');
        }

        $deck = $session->get('deck', new DeckOfCards());

        if ($num > $deck->count()) {
            $this->addFlash('warning', 'Not enough cards left in the deck!');

            return $this->redirectToRoute('deck_draw');
        }

        $drawn = $deck->draw($num);
        $session->set('deck', $deck);

        $cards = [];
        foreach ($drawn as $card) {
            $cards[] = [
                'symbol' => $card->getAsString(),
                'color' => $card->getColor(),
            ];
        }

        $data = [
            'cards' => $cards,
            'num_remaining' => $deck->count(),
        ];

        return $this->render('deck/draw.html.twig', $data);
    }
}
