<?php

namespace App\Controller;

use App\Game\BlackjackGame;
use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProjController extends AbstractController
{
    #[Route('/proj', name: 'proj_home')]
    public function home(): Response
    {
        return $this->render('proj/home.html.twig');
    }

    #[Route('/proj/about', name: 'proj_about')]
    public function about(): Response
    {
        return $this->render('proj/about.html.twig');
    }

    #[Route('/proj/lobby', name: 'proj_lobby')]
    public function lobby(): Response
    {
        return $this->render('proj/lobby.html.twig');
    }

    #[Route('/proj/start', name: 'proj_start', methods: ['POST'])]
    public function start(
        Request $request,
        SessionInterface $session,
        EntityManagerInterface $em,
        PlayerRepository $playerRepository
    ): Response {
        $name = $request->request->get('name');
        $numHands = (int) $request->request->get('num_hands', 1);
        $bet = (int) $request->request->get('bet', 50);

        $player = $playerRepository->findOneBy(['name' => $name]);
        if (!$player) {
            $player = new Player();
            $player->setName($name);
            $player->setBalance(1000);
            $em->persist($player);
            $em->flush();
        }

        $session->set('player_id', $player->getId());
        $session->set('bet', $bet);
        $session->set('num_hands', $numHands);

        return $this->redirectToRoute('proj_game_play');
    }

    #[Route('/proj/game', name: 'proj_game')]
    public function game(): Response
    {
        return $this->render('proj/game_landing.html.twig');
    }

    #[Route('/proj/game/play', name: 'proj_game_play', methods: ['GET'])]
    public function play(
        SessionInterface $session,
        PlayerRepository $playerRepository
    ): Response {
        $playerId = $session->get('player_id');
        $player = $playerRepository->find($playerId);
        $numHands = $session->get('num_hands', 1);
        $bet = $session->get('bet', 50);

        $game = new BlackjackGame($numHands);
        $game->deal();
        $session->set('proj_game', $game);

        return $this->render('proj/game.html.twig', [
            'game' => $game,
            'player' => $player,
            'bet' => $bet,
            'dealerHand' => [$game->getDealerHand()->getString()[0], '🂠'],
            'dealerScore' => $game->getDealerVisibleScore(),
        ]);
    }

    #[Route('/proj/game/hit', name: 'proj_hit', methods: ['POST'])]
    public function projHit(
        SessionInterface $session,
        PlayerRepository $playerRepository
    ): Response {
        $game = $session->get('proj_game');
        $game->hit();
        $session->set('proj_game', $game);

        $player = $playerRepository->find($session->get('player_id'));
        $bet = $session->get('bet', 50);
        $roundOver = $game->isRoundOver();

        return $this->render('proj/game.html.twig', [
            'game' => $game,
            'player' => $player,
            'bet' => $bet,
            'dealerHand' => $roundOver
                ? $game->getDealerHand()->getString()
                : [$game->getDealerHand()->getString()[0], '🂠'],
            'dealerScore' => $roundOver
                ? $game->calculateScore($game->getDealerHand())
                : $game->getDealerVisibleScore(),
        ]);
    }

    #[Route('/proj/game/stand', name: 'proj_stand', methods: ['POST'])]
    public function projStand(
        SessionInterface $session,
        PlayerRepository $playerRepository,
        EntityManagerInterface $em
    ): Response {
        $game = $session->get('proj_game');
        $game->stand();
        $session->set('proj_game', $game);

        $player = $playerRepository->find($session->get('player_id'));
        $bet = $session->get('bet', 50);
        $roundOver = $game->isRoundOver();

        // Uppdatera balansen när rundan är över
        if ($roundOver) {
            $numHands = $game->getNumHands();
            for ($i = 0; $i < $numHands; $i++) {
                $status = $game->getHandStatus($i);
                if (in_array($status, ['player_win', 'dealer_bust'])) {
                    $player->setBalance($player->getBalance() + $bet);
                } elseif (in_array($status, ['player_bust', 'player_lost'])) {
                    $player->setBalance($player->getBalance() - $bet);
                }
            }
            $em->flush();
        }

        return $this->render('proj/game.html.twig', [
            'game' => $game,
            'player' => $player,
            'bet' => $bet,
            'dealerHand' => $roundOver
                ? $game->getDealerHand()->getString()
                : [$game->getDealerHand()->getString()[0], '🂠'],
            'dealerScore' => $roundOver
                ? $game->calculateScore($game->getDealerHand())
                : $game->getDealerVisibleScore(),
        ]);
    }
}