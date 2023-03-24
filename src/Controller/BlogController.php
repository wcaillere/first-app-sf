<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Tag;
use App\Entity\Theme;
use App\Entity\User;
use App\Factory\UserFactory;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        ]);
    }

    #[Route('/{id}', name: 'details', requirements: ['id' => '\d+'])]
    public function details(Article                $article,
                            Request                $request,
                            UserRepository         $repository,
                            EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $comment->setArticle($article);
        // Simulation de la connexion d'un des utilisateurs
        $comment->setAuthor($repository->find(1558));
        $comment->setCreatedAt(new \DateTime('now'));

        $form = $this->createForm(CommentType::class, $comment, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('blog_details', ['id' => $article->getId()]);
        }

        return $this->render('blog/details.html.twig', [
            'article'     => $article,
            'CommentForm' => $form->createView()
        ]);
    }

    #[Route('/byTheme/{id}', name: 'byTheme')]
    public function byTheme(Theme $theme, ArticleRepository $repository): Response
    {
        $articleList = $repository->findBy(['theme' => $theme]);

        return $this->render(
            'blog/list.html.twig',
            [
                'articleList' => $articleList,
                'title'       => 'Liste des articles par theme',
                'crit'        => $theme->getThemeName()
            ]
        );
    }

    #[Route('/byAuthor/{id}', name: 'byAuthor')]
    public function byAuthor(User $author, ArticleRepository $repository): Response
    {
        $articleList = $repository->findBy(['author' => $author]);

        return $this->render(
            'blog/list.html.twig',
            [
                'articleList' => $articleList,
                'title'       => 'Liste des articles par auteur',
                'crit'        => $author->getNickName()
            ]
        );
    }

    #[Route('/byTag/{id}', name: 'byTag')]
    public function byTag(Tag $tag, ArticleRepository $repository): Response
    {
        $articleList = $repository->getArticlesByTag($tag);

        return $this->render(
            'blog/list.html.twig',
            [
                'articleList' => $articleList,
                'title'       => 'Liste des articles par tag',
                'crit'        => $tag->getTagName()
            ]
        );
    }

    #[Route('/aside', name: 'aside')]
    public function aside(ArticleRepository $repository): Response
    {
        $countByAuthor = $repository->getArticleCountByAuthor()
                                    ->getResult();

        $countByTag = $repository->getArticleCountByTag()
                                 ->getResult();

        return $this->render(
            'blog/aside.html.twig',
            [
                'countByAuthor' => $countByAuthor,
                'countByTag'    => $countByTag,
                'countByYear'   => $repository->getArticleCountByYear()
            ]
        );
    }
}