<?php

namespace App\Controller;

use App\Service\AppService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/author')]
class AuthorController extends AbstractController
{
    private AppService $service;

    /**
     * @param AppService $service
     */
    public function __construct(AppService $service)
    {
        $this->service = $service;
    }

    #[Route('/', name: 'author_index')]
    public function index(): Response
    {
        dump($this->service->getAuthorList());
        return $this->render("author/list.html.twig", ['authorList' => $this->service->getAuthorList()]);
    }

    #[Route('/{id}', name: 'author_details', requirements: ['id' => '\d+'])]
    public function details(int $id): Response
    {
        return $this->render("author/details.html.twig", ['author' => $this->service->getAuthor($id)]);
    }
}