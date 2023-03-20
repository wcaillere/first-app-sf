<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/home')]
class HomeController extends AbstractController
{
    #[Route('/hello', name: 'hello')]
    public function index(): Response
    {
        return $this->render('home_hello.html.twig');
    }

    #[Route("/details/{id}/{name}",
        name: 'details',
        requirements: ['id' => '\d+'],
        defaults: ['id' => '23', 'name' => 'michel'],
        methods: ['GET', 'POST'])]
    public function details(int $id, string $name): Response
    {
        return $this->render(
            'home_details.html.twig',
            [
                'id' => $id,
                'name' => $name,
                'person' => ['name' => 'Hugo', 'firstName' => 'Victor']
            ]
        );
    }
}