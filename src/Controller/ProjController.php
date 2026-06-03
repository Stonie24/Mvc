<?php

namespace App\Controller;

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
}