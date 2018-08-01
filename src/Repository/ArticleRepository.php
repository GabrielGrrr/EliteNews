<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function listArticles() : array
    {
    $conn = $this->getEntityManager()->getConnection();

    //Plain old sql ici, pour des raisons d'optimisation
    $sql = 'SELECT a.id, a.titre, a.category, a.date_creation, a.content, a.weight, a.image, u.login as author, COUNT(c.id) as comment_count 
    FROM article a, thread t, user u, comment c
    WHERE a.author_id = u.id 
    AND t.article_id = a.id 
    AND c.thread_id = t.id 
    AND a.removed = 0
    GROUP BY a.id
    ORDER BY a.date_creation DESC
    LIMIT 20';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
    }

    public function getSlideArticles()
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.weight = 2')
            ->orderBy('a.date_creation', 'DESC')
            ->setMaxResults(5)
            ->getQuery();

        return $qb->execute();
    }

    //En l'absence de keyword LEAD et LAG, on fait comme on peut
    public function getPreviousNext($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT max(id) as previous_row
        FROM article
        WHERE id < '.$id;
        $previous = $conn->prepare($sql);
        $previous->execute();

        $sql = 'SELECT min(id) as next_row
        FROM article
        WHERE id > '.$id;
        $next = $conn->prepare($sql);
        $next->execute();

    return [$previous->fetchAll(), $next->fetchall()];
    }
    

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
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
