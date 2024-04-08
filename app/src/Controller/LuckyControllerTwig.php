<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerTwig extends AbstractController
{
    #[Route("/lucky", name: "lucky")]
    public function lucky(): Response
    {
        $colors = array("red", "blue", "green", "yellow", "orange", "purple");

        $randomColor = $this->pickRandomColor($colors);

        $data = [
            'color' => $randomColor
        ];

        return $this->render('lucky.html.twig', $data);
    }

    // Function to pick a random color from the array
    private function pickRandomColor($colors) {
        // Generate a random index within the range of the array length
        $randomIndex = rand(0, count($colors) - 1);
        
        // Return the color at the random index
        return $colors[$randomIndex];
    }

    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route("/report/{kmom?}", name: "report")]
    public function report($kmom = null): Response
    {
        return $this->render('report.html.twig', [
            'kmom' => $kmom
        ]);
    }
}
