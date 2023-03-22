<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use App\Service\AppService;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(AuthorRepository $repository): Response
    {
        dump($this->service->getAuthorList());
        return $this->render("author/list.html.twig", ['authorList' => $repository->findAll()]);
    }

    #[Route('/{id}', name: 'author_details', requirements: ['id' => '\d+'])]
    public function details(int $id, AuthorRepository $repository): Response
    {
        return $this->render("author/details.html.twig", ['author' => $repository->findOneBy(['id' => $id])]);
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

    #[Route('/form', name: 'author_insert_form')]
    #[Route('/form/{id}', name: 'author_update_form', requirements: ['id' => '\d+'])]
    public function form(Request $request, EntityManagerInterface $entityManager, Author $author = null): Response
    {
        // Création de l'entité
        if ($author === null) {
            $author = new Author();
        }
        // Création du formulaire
        $form = $this->createForm(AuthorType::class, $author, []);
        // Hydratation du formulaire
        $form->handleRequest($request);

        // Traitement des données postées
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde
            $entityManager->persist($author);
            $entityManager->flush();

            // Redirection
            return $this->redirectToRoute('author_index');
        };

        // Affichage de la vue
        return $this->render('author/form.html.twig', ['authorForm' => $form->createView()]);
    }

    #[Route('/delete/{id}', name: 'author_delete', requirements: ['id' => '\d+'])]
    public function delete(EntityManagerInterface $entityManager, Author $author): Response
    {
        $entityManager->remove($author);
        $entityManager->flush();

        return $this->redirectToRoute('author_index');
    }
}