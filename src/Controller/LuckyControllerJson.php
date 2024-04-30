<?php

namespace App\Controller;
use App\Deck\Deck;
use App\Deck\DeckGraphic;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LuckyControllerJson
{
    #[Route("/api")]
    public function jsonRoutes(): Response
    {
        $number = random_int(0, 100);

        $data = [
            "card_start" => "ANY      ANY      ANY    /card",
            "card_deck" => "ANY      ANY      ANY    /card/deck",
            "deck_shuffle" => "ANY      ANY      ANY    /card/deck/shuffle",
            "deck_draw" => "ANY      ANY      ANY    /card/deck/draw",
            "deck_draw_num_cards" => "ANY      ANY      ANY    /card/deck/draw/{num}",
            "pig_start" => "ANY      ANY      ANY    /game/pig",
            "test_roll_dice" => "ANY      ANY      ANY    /game/pig/test/roll",
            "test_roll_num_dices" => "ANY      ANY      ANY    /game/pig/test/roll/{num}",
            "test_dicehand" => "ANY      ANY      ANY    /game/pig/test/dicehand/{num}",
            "pig_init_get" => "GET      ANY      ANY    /game/pig/init",
            "pig_init_post" => "POST     ANY      ANY    /game/pig/init",
            "pig_play" => "GET      ANY      ANY    /game/pig/play",
            "pig_roll" => "POST     ANY      ANY    /game/pig/roll",
            "pig_save" => "POST     ANY      ANY    /game/pig/save",
            "app_lucky_hi"  => " ANY      ANY      ANY    /lucky/hi",
            "lucky" => "ANY      ANY      ANY    /lucky",
            "home" => "ANY      ANY      ANY    /",
            "about" => "ANY      ANY      ANY    /about",
            "report" => "ANY      ANY      ANY    /report/{kmom}"
        ];


        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/quote")]
    public function jsonQuote(): Response
    {
        $quote = random_int(0, 2);

        $list = [
            "Det var en gång en gång och den var sandad",
            "Här kommer göran göran ade göran",
            "här har en hare hoppat hare? a det hare"
        ];

        $data = [
            'quote' => $list[$quote],
        ];


        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck", name: "get_deck")]
    public function getDeck(): Response
    {
        $num = 52;
        $deckList = [];
        for ($i = 1; $i <= $num; $i++) {
            $deck = new DeckGraphic();
            $deck->assign($i);
            $deckList[] = $deck->getAsString();
        }

        $data = [
            "deckList" => $deckList,
        ];


        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", name: "shuffle_deck")]
    public function getShuffledDeck(SessionInterface $session): Response
    {
        $session->remove('drawn_cards');
        $num = 51;
        $numberList = range(1, 52);
        shuffle($numberList);
        $deckList = [];
        for ($i = 0; $i <= $num; $i++) {
            $deck = new DeckGraphic();
            $deck->assign($numberList[$i]);
            $deckList[] = $deck->getAsString();
        }

        $data = [
            "deckList" => $deckList,
        ];


        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw", name: "deck_draw")]
    public function deckDraw(SessionInterface $session): Response
    {
        $drawnCards = $session->get('drawn_cards', []);
        if (count($drawnCards) >= 52) {
            $drawnCards[0] = "No cards lef too draw";
        } else {
            $randomNumber = rand(1, 52 - count($drawnCards));
            
            $deck = new DeckGraphic();
            foreach ($drawnCards as $cards) {
                $deck->pop($cards);;
            }
            $deck->assign($randomNumber);
            $drawnCard = $deck->getAsString();
            
            
            
            
            $drawnCards[] = $drawnCard;
            
            
            $session->set('drawn_cards', $drawnCards);
            
            
            
        }

        $data = [
            "deckList" => $drawnCards,
        ];


        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw/{num<\d+>}", name: "deck_draw_num")]
    public function deckDrawNum(int $num, SessionInterface $session): Response
    {
        $drawnCards = $session->get('drawn_cards', []);
        if ($num > 52) {
            throw new \Exception("Can not draw more than 52 cards!");
        } elseif (count($drawnCards) + $num >= 52) {
            $deckList[0] = "Not enough cards lef too draw";
        } else {
            $numberList = range(1, 52 - count($drawnCards));
            shuffle($numberList);
            
            $deck = new DeckGraphic();
            foreach ($drawnCards as $cards) {
                $deck->pop($cards);;
            }

            $deckList = [];
            for ($i = 1; $i <= $num; $i++) {
                // $deck = new DeckGraphic();
                $deck->assign($numberList[$i]);
                $drawnCard = $deck->getAsString();
                $drawnCards[] = $drawnCard;
                $deckList[] = $drawnCard;
            }

            $session->set('drawn_cards', $drawnCards);
        }

        $data = [
            "deckList" => $deckList,
        ];


        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

}
