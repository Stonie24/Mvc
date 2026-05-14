<?php

namespace App\Controller;

use App\Game\BlackjackGame;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/game', name: 'game_start')]
    public function home(): Response
    {
        return $this->render('game/home.html.twig');
    }

    #[Route('/game/play', name: 'game_play', methods: ['GET'])]
    public function play(SessionInterface $session): Response
    {
        $game = new BlackjackGame();
        $game->deal();
        $session->set('game', $game);

        $status = $game->getGameStatus();
        $dealerScore = 'playing' === $status
            ? $game->getDealerVisibleScore()
            : $game->calculateScore($game->getDealerHand());

        return $this->render('game/game.html.twig', [
            'playerHand' => $game->getPlayerHand()->getString(),
            'dealerHand' => [$game->getDealerHand()->getString()[0], '🂠'],
            'playerScore' => $game->calculateScore($game->getPlayerHand()),
            'gameStatus' => $status,
            'dealerScore' => $dealerScore,
        ]);
    }

    #[Route('/game/hit', name: 'game_hit', methods: ['POST'])]
    public function hit(SessionInterface $session): Response
    {
        $game = $session->get('game');
        $game->hit();
        $session->set('game', $game);

        $status = $game->getGameStatus();
        $dealerHand = 'playing' === $status
            ? [$game->getDealerHand()->getString()[0], '🂠']
            : $game->getDealerHand()->getString();
        $dealerScore = 'playing' === $status
            ? $game->getDealerVisibleScore()
            : $game->calculateScore($game->getDealerHand());

        return $this->render('game/game.html.twig', [
            'playerHand' => $game->getPlayerHand()->getString(),
            'dealerHand' => $dealerHand,
            'playerScore' => $game->calculateScore($game->getPlayerHand()),
            'gameStatus' => $status,
            'dealerScore' => $dealerScore,
        ]);
    }

    #[Route('/game/stand', name: 'game_stand', methods: ['POST'])]
    public function stand(SessionInterface $session): Response
    {
        $game = $session->get('game');
        $game->stand();
        $session->set('game', $game);

        $status = $game->getGameStatus();
        $dealerScore = 'playing' === $status
            ? $game->getDealerVisibleScore()
            : $game->calculateScore($game->getDealerHand());

        return $this->render('game/game.html.twig', [
            'playerHand' => $game->getPlayerHand()->getString(),
            'dealerHand' => $game->getDealerHand()->getString(),
            'playerScore' => $game->calculateScore($game->getPlayerHand()),
            'gameStatus' => $status,
            'dealerScore' => $dealerScore,
        ]);
    }

    #[Route('/game/doc', name: 'game_doc')]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }

    #[Route('/api/game', name: 'game_api')]
    public function api(SessionInterface $session): Response
    {
        $game = $session->get('game');

        if (!$game) {
            return $this->json(['error' => 'No game in session']);
        }

        return $this->json([
            'playerScore' => $game->calculateScore($game->getPlayerHand()),
            'dealerScore' => $game->calculateScore($game->getDealerHand()),
            'gameStatus' => $game->getGameStatus(),
            'playerHand' => $game->getPlayerHand()->getString(),
            'dealerHand' => $game->getDealerHand()->getString(),
        ]);
    }
}
