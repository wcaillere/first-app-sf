<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog', name: 'blog_')]
class BlogController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ArticleRepository $repository): Response
    {

        return $this->render('blog/index.html.twig', [
                'articleList' => $repository->findBy([], ['createdAt' => 'DESC'], 4)
            ]
        );
    }
}