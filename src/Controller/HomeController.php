<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/home')]
class HomeController
{
    #[Route('/hello', name: 'hello')]
    public function index() {
        return new Response("Hello Symfony");
    }

    #[Route("/details/{id}",
        name: 'details',
        requirements: ['id' => '\d+'],
        defaults: ['id' => '23'],
        methods: ['GET', 'POST'])]
    public function details(int $id) {
        return new Response("produit $id");
    }
}