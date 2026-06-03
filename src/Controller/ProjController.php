<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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

    #[Route('/proj/game', name: 'proj_game')]
    public function game(): Response
    {
        return $this->render('proj/game_landing.html.twig');
    }

}