<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerJson
{

    #[Route("/api")]
    public function jsonRoutes(): Response
    {
        $number = random_int(0, 100);

        $data = [
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
}