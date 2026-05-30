<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{
    #[Route('/library', name: 'library_home')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig');
    }

    #[Route('/library/create', name: 'library_create_get', methods: ['GET'])]
    public function createGet(): Response
    {
        return $this->render('library/create.html.twig');
    }

    #[Route('/library/create', name: 'library_create_post', methods: ['POST'])]
    public function createPost(
        Request $request,
        ManagerRegistry $doctrine,
    ): Response {
        $entityManager = $doctrine->getManager();

        $book = new Book();
        $book->setTitle($request->request->get('title'));
        $book->setIsbn($request->request->get('isbn'));
        $book->setAuthor($request->request->get('author'));
        $book->setImage($request->request->get('image'));

        $entityManager->persist($book);
        $entityManager->flush();

        $this->addFlash('notice', 'Book was added!');

        return $this->redirectToRoute('library_show_all');
    }

    #[Route('/library/show', name: 'library_show_all')]
    public function showAll(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();

        return $this->render('library/show_all.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/library/show/{id}', name: 'library_show_one')]
    public function showOne(BookRepository $bookRepository, int $id): Response
    {
        $book = $bookRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException('No book found for id '.$id);
        }

        return $this->render('library/show_one.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/library/update/{id}', name: 'library_update_get', methods: ['GET'])]
    public function updateGet(BookRepository $bookRepository, int $id): Response
    {
        $book = $bookRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException('No book found for id '.$id);
        }

        return $this->render('library/update.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/library/update/{id}', name: 'library_update_post', methods: ['POST'])]
    public function updatePost(
        Request $request,
        ManagerRegistry $doctrine,
        int $id,
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException('No book found for id '.$id);
        }

        $book->setTitle($request->request->get('title'));
        $book->setIsbn($request->request->get('isbn'));
        $book->setAuthor($request->request->get('author'));
        $book->setImage($request->request->get('image'));

        $entityManager->flush();

        $this->addFlash('notice', 'Book was updated!');

        return $this->redirectToRoute('library_show_all');
    }

    #[Route('/library/delete/{id}', name: 'library_delete', methods: ['POST'])]
    public function delete(
        ManagerRegistry $doctrine,
        int $id,
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException('No book found for id '.$id);
        }

        $entityManager->remove($book);
        $entityManager->flush();

        $this->addFlash('notice', 'Book was deleted!');

        return $this->redirectToRoute('library_show_all');
    }
}
