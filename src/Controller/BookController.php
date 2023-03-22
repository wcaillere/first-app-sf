<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book', name: 'book_')]
class BookController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(BookRepository $repository): Response
    {

        return $this->render('book/index.html.twig', ['bookList' => $repository->findAll()]);
    }

    #[Route('/form', name: 'insert_form')]
    public function form(Request $request, BookRepository $repository): Response
    {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($book, true);
        };

        return $this->render('book/form.html.twig', ['bookForm' => $form->createView()]);
    }
}