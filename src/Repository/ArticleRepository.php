<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
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

    public function findArticleByPriceLessThan($prix)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.prix < :val')
            ->setParameter('val', $prix)
           // ->orderBy('a.id', 'ASC')
           // ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function filterArticleBy($mot)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT * FROM Article a
                WHERE a.titre like :mot
                ORDER BY a.titre ASC';

        $stmt = $conn->prepare($sql);
        $stmt->execute(['mot' => '%'.$mot.'%']);

        return $stmt->fetchAllAssociativeIndexed();
    }

    public function filterArticlesBy($mot)
    {
        return $this->createQueryBuilder('Article')
        ->andWhere('Article.titre LIKE :val')
        ->setParameter('val', '%'.$mot.'%')
        ->getQuery()
        ->execute();
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
