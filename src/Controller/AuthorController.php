<?php

namespace App\Controller;

use App\Entity\Author;
use App\Service\AppService;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
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

    #[Route('/new/{firstName}/{lastName}', name: 'author_new')]
    public function newAuthor(string $firstName, string $lastName, EntityManagerInterface $entityManager): Response
    {
        // Instance de Faker
        $faker = Factory::create();

        $author = new Author();
        // Hydratation de l'entité avec les paramètres de la route
        $author
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setBio($faker->realText(2000));

        dump($author);
        // Sauvegarde de l'auteur avec Doctrine
        $entityManager->persist($author);
        $entityManager->flush();

        return $this->render('author/new.html.twig', ['author' => $author]);
    }
}