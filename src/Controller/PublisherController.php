<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Repository\PublisherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/publisher', name: 'publisher_')]
class PublisherController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(PublisherRepository $repository): Response
    {
        return $this->render('publisher/list.html.twig', ['publisherList' => $repository->findAll()]);
    }

    #[Route('/{id}', name: 'details', requirements: ['id' => '\d+'])]
    public function details(int $id, PublisherRepository $repository): Response
    {
        return $this->render('publisher/details.html.twig', ['publisher' => $repository->findOneBy(['id' => $id])]);
    }

    #[Route('/new/{name}', name: 'new')]
    public function newPublisher(string $name, EntityManagerInterface $entityManager): Response
    {
        $publisher = new Publisher();

        $publisher->setName($name);

        $entityManager->persist($publisher);
        $entityManager->flush();

        return $this->render('publisher/new.html.twig', ['publisher' => $publisher]);
    }
}