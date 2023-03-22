<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\PublisherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    #[Route('form/{id}', name: 'form_update', requirements: ['id' => '\d+'])]
    public function form(
        Request                $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface     $validator,
        Publisher              $publisher = null
    ): Response
    {
        $errors = [];

        $actionName = 'modification';
        if ($publisher === null) {
            $publisher = new Publisher();
            $actionName = 'ajout';
        }

        $form = $this->createForm(PublisherType::class, $publisher, ['attr' => ['novalidate' => 'novalidate']]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $errors = $validator->validate($publisher);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($publisher);
            $entityManager->flush();

            $this->addFlash('success', "votre $actionName est un succès pour l'auteur " . $publisher->getName());

            return $this->redirectToRoute('publisher_index');
        }

        return $this->render('publisher/form.html.twig', [
            'publisherForm' => $form->createView(),
            'errors' => $errors
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(int $id, PublisherRepository $repository): Response
    {
        try {
            $publisher = $repository->find($id);
            $publisherName = $publisher->getName();

            $repository->remove($publisher, true);

            $this->addFlash('success', "L'auteur $publisherName a bien été supprimé");

        } catch (\Throwable $ex) {
            $this->addFlash('error', "impossible de trouver l'auteur à supprimer");
        }

        return $this->redirectToRoute('publisher_index');
    }
}