<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibraryApiController extends AbstractController
{
    #[Route('/api/library/books', name: 'api_library_books', methods: ['GET'])]
    public function apiGetBooks(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();

        $data = array_map(function ($book) {
            return [
                'id'     => $book->getId(),
                'title'  => $book->getTitle(),
                'author' => $book->getAuthor(),
                'isbn'   => $book->getIsbn(),
                'image'  => $book->getImage(),
            ];
        }, $books);

        $response = $this->json($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route('/api/library/book/{isbn}', name: 'api_library_book_by_isbn', methods: ['GET'])]
    public function apiGetBookByIsbn(BookRepository $bookRepository, string $isbn): Response
    {
        $book = $bookRepository->findOneBy(['isbn' => $isbn]);

        if (!$book) {
            return $this->json(['error' => 'No book found with ISBN '.$isbn], 404);
        }

        $data = [
            'id'     => $book->getId(),
            'title'  => $book->getTitle(),
            'author' => $book->getAuthor(),
            'isbn'   => $book->getIsbn(),
            'image'  => $book->getImage(),
        ];

        $response = $this->json($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}