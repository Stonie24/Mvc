<?php

namespace App\Controller;

use App\Deck\Deck;
use App\Deck\DeckGraphic;
use App\Deck\DeckHand;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeckGameController extends AbstractController
{
    #[Route("/card", name: "card_start")]
    public function home(): Response
    {
        return $this->render('deck/home.html.twig');
    }

    #[Route("/card/deck", name: "card_deck")]
    public function displayDeck(): Response
    {
        $num = 52;
        $deckList = [];
        for ($i = 1; $i <= $num; $i++) {
            $deck = new DeckGraphic();
            $deck->assign($i);
            $deckList[] = $deck->getAsString();
        }

        $data = [
            "num_of_cards" => 52 - count($deckList),
            "deckList" => $deckList,
        ];

        return $this->render('deck/test/roll.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "deck_shuffle")]
    public function testShuffleDeck(): Response
    {
        $num = 51;
        $numberList = range(1,52);
        shuffle($numberList);
        $deckList = [];
        for ($i = 0; $i <= $num; $i++) {
            $deck = new DeckGraphic();
            $deck->assign($numberList[$i]);
            $deckList[] = $deck->getAsString();
        }

        $data = [
            "num_of_cards" => 52 - count($deckList),
            "deckList" => $deckList,
        ];

        return $this->render('deck/test/roll.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "deck_draw")]
    public function deckDraw(): Response
    {
        $randomNumber = rand(1, 52);
        $deckList = [];

        $deck = new DeckGraphic();
        $deck->assign($randomNumber);
        $deckList[] = $deck->getAsString();

        $data = [
            "num_of_cards" => 52 - count($deckList),
            "deckList" => $deckList,
        ];

        return $this->render('deck/test/roll.html.twig', $data);
    }

    #[Route("/card/deck/draw/{num<\d+>}", name: "deck_draw_num_cards")]
    public function drawNumCards(int $num): Response
    {
        if ($num > 52) {
            throw new \Exception("Can not roll more than 52 dices!");
        }

        $numberList = range(1,52);
        shuffle($numberList);
        $deckList = [];
        for ($i = 1; $i <= $num; $i++) {
            $deck = new DeckGraphic();
            $deck->assign($numberList[$i]);
            $deckList[] = $deck->getAsString();
        }

        $data = [
            "num_of_cards" => 52 - count($deckList),
            "deckList" => $deckList,
        ];

        return $this->render('deck/test/roll.html.twig', $data);
    }

    // #[Route("/card/pig/test/roll/{num<\d+>}", name: "test_roll_num_dices")]
    // public function testRollDices(int $num): Response
    // {
    //     if ($num > 99) {
    //         throw new \Exception("Can not roll more than 99 dices!");
    //     }

    //     $diceRoll = [];
    //     for ($i = 1; $i <= $num; $i++) {
    //         // $die = new Dice();
    //         $die = new DiceGraphic();
    //         $die->roll();
    //         $diceRoll[] = $die->getAsString();
    //     }

    //     $data = [
    //         "num_dices" => count($diceRoll),
    //         "diceRoll" => $diceRoll,
    //     ];

    //     return $this->render('pig/test/roll_many.html.twig', $data);
    // }

    // #[Route("/card/pig/test/dicehand/{num<\d+>}", name: "test_dicehand")]
    // public function testDiceHand(int $num): Response
    // {
    //     if ($num > 99) {
    //         throw new \Exception("Can not roll more than 99 dices!");
    //     }

    //     $hand = new DiceHand();
    //     for ($i = 1; $i <= $num; $i++) {
    //         if ($i % 2 === 1) {
    //             $hand->add(new DiceGraphic());
    //         } else {
    //             $hand->add(new Dice());
    //         }
    //     }

    //     $hand->roll();

    //     $data = [
    //         "num_dices" => $hand->getNumberDices(),
    //         "diceRoll" => $hand->getString(),
    //     ];

    //     return $this->render('pig/test/dicehand.html.twig', $data);
    // }

    // #[Route("/card/pig/init", name: "pig_init_get", methods: ['GET'])]
    // public function init(): Response
    // {
    //     return $this->render('pig/init.html.twig');
    // }

    // #[Route("/card/pig/init", name: "pig_init_post", methods: ['POST'])]
    // public function initCallback(
    //     Request $request,
    //     SessionInterface $session
    // ): Response
    // {
    //     $numDice = $request->request->get('num_dices');

    //     $hand = new DiceHand();
    //     for ($i = 1; $i <= $numDice; $i++) {
    //         $hand->add(new DiceGraphic());
    //     }
    //     $hand->roll();

    //     $session->set("pig_dicehand", $hand);
    //     $session->set("pig_dices", $numDice);
    //     $session->set("pig_round", 0);
    //     $session->set("pig_total", 0);

    //     return $this->redirectToRoute('pig_play');
    // }

    // #[Route("/card/pig/play", name: "pig_play", methods: ['GET'])]
    // public function play(
    //     SessionInterface $session
    // ): Response
    // {
    //     $dicehand = $session->get("pig_dicehand");

    //     $data = [
    //         "pigDices" => $session->get("pig_dices"),
    //         "pigRound" => $session->get("pig_round"),
    //         "pigTotal" => $session->get("pig_total"),
    //         "diceValues" => $dicehand->getString() 
    //     ];

    //     return $this->render('pig/play.html.twig', $data);
    // }

    // #[Route("/card/pig/roll", name: "pig_roll", methods: ['POST'])]
    // public function roll(
    //     SessionInterface $session
    // ): Response
    // {
    //     $hand = $session->get("pig_dicehand");
    //     $hand->roll();

    //     $roundTotal = $session->get("pig_round");
    //     $round = 0;
    //     $values = $hand->getValues();
    //     foreach ($values as $value) {
    //         if ($value === 1) {
    //             $round = 0;
    //             $roundTotal = 0;

    //             $this->addFlash(
    //                 'warning',
    //                 'You got a 1 and you lost the round points!'
    //             );
                
    //             break;
    //         }
    //         $round += $value;
    //     }

    //     $session->set("pig_round", $roundTotal + $round);
        
    //     return $this->redirectToRoute('pig_play');
    // }

    // #[Route("/card/pig/save", name: "pig_save", methods: ['POST'])]
    // public function save(
    //     SessionInterface $session
    // ): Response
    // {   
    //     $roundTotal = $session->get("pig_round");
    //     $gameTotal = $session->get("pig_total");

    //     $session->set("pig_round", 0);
    //     $session->set("pig_total", $roundTotal + $gameTotal);

    //     $this->addFlash(
    //         'notice',
    //         'Your round was saved to the total!'
    //     );

    //     return $this->redirectToRoute('pig_play');
    // }
}