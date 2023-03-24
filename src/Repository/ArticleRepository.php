<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function save(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()
             ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                 ->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()
             ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                 ->flush();
        }
    }

    public function getArticleCountByAuthor(): Query
    {
        $qb = $this->createQueryBuilder('article')
                   ->select('a.id as author_id, a.nickName, count(article.id) as nb')
                   ->join('article.author', 'a')
                   ->groupBy('a.id');

        return $qb->getQuery();
    }

    public function getArticleCountByTag(): Query
    {
        $qb = $this->createQueryBuilder('article')
                   ->select('t.id as tag_id, t.tagName, count(article.id) as nb')
                   ->join('article.tags', 't')
                   ->groupBy('t.id');

        return $qb->getQuery();
    }

    public function getArticleCountByYear()
    {
        return $this->createQueryBuilder('article')
                    ->select('year(article.publishedAt) as yearPublished, count(article.id) as nb')
                    ->groupBy('yearPublished')
                    ->getQuery()
                    ->getArrayResult();
    }

    public function getArticlesByTag(Tag $tag)
    {
        return $this->createQueryBuilder('a')
                    ->select('a')
                    ->join('a.tags', 't')
                    ->where('t.tagName=:tagName')
                    ->setParameter(':tagName', $tag->getTagName())
                    ->getQuery()
                    ->getResult();
    }

    public function getArticlesByYear(int $year)
    {
        return $this->createQueryBuilder('a')
                    ->select('a')
                    ->where('year(a.publishedAt)=:year')
                    ->setParameter(':year', $year)
                    ->orderBy('a.publishedAt', 'DESC')
                    ->getQuery()
                    ->getResult();
    }

    public function getArticleAverageRating(int $id)
    {
        return $this->createQueryBuilder('a')
                    ->select('a.id, avg(c.rating) as note')
                    ->join('a.comments', 'c')
                    ->where('c.article=:id')
                    ->setParameter(':id', $id)
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    public function getArticlesByRating(int $nb)
    {
        return $this->createQueryBuilder('a')
                    ->select('a, avg(c.rating) as rating')
                    ->join('a.comments', 'c')
                    ->setMaxResults($nb)
                    ->orderBy('rating', 'DESC')
                    ->groupBy('a.id')
                    ->getQuery()
                    ->getResult();
    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
