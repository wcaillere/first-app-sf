<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\PublisherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/form', name: 'form')]
    public function form(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publisher = new Publisher();

        $form = $this->createForm(PublisherType::class, $publisher, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($publisher);
            $entityManager->flush();

            return $this->redirectToRoute('publisher_index');
        }

        return $this->render('publisher/form.html.twig', ['publisherForm' => $form->createView()]);
    }
}