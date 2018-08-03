<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

define('ARTICLES_PAR_PAGE', '20');

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

    public function listArticles($index = 0) : array
    {
    $conn = $this->getEntityManager()->getConnection();

    //Plain old sql ici, pour des raisons d'optimisation
    $sql = 'SELECT a.id, a.titre, a.category, a.date_creation, a.content, a.weight, a.image, u.login as author, COUNT(c.id) as comment_count 
    FROM article a, thread t, app_users u, comment c
    WHERE a.author_id = u.id 
    AND t.article_id = a.id 
    AND c.thread_id = t.id 
    AND a.removed = 0
    GROUP BY a.id
    ORDER BY a.date_creation DESC
    LIMIT '.($index * ARTICLES_PAR_PAGE).', '.ARTICLES_PAR_PAGE;
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
    }

    public function getArticlePageCount()
    {
        return ($this->createQueryBuilder('a')
        ->select('COUNT(a)')
         ->getQuery()
        ->getSingleScalarResult() / ARTICLES_PAR_PAGE);
    }

    //Faire une rqst personnalisée plus tard pour minimiser les appels base quand on boucle dans la vue
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
        $sql = 'SELECT max(a.id) as previous_row
        FROM article a
        WHERE a.removed = 0
        AND a.id < '.$id;
        $previous = $conn->prepare($sql);
        $previous->execute();

        $sql = 'SELECT min(a.id) as next_row
        FROM article a
        WHERE a.removed = 0
        AND a.id > '.$id;
        $next = $conn->prepare($sql);
        $next->execute();

    return [$previous->fetchAll(), $next->fetchall()];
    }

    //A optimiser impérativement lol, à la fois SQL et code (obtenir compteurs et validation en ammont) et prendre en compte regexp
    //Ajouter options sélection inclusive ou exclusive
    public function search($input, $categories, $contentToo = null)
    {
        if($input == '*') $input = NULL;
        $discrim = NULL;
        $c = NULL;
        
        if(isset($input)) { $discrim = explode(' ', $input);
        $c = count($discrim); }
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT a.id, a.titre, a.category, a.date_creation, a.content, a.weight, a.image, u.login as author, COUNT(c.id) as comment_count 
        FROM article a, thread t, app_users u, comment c
        WHERE a.author_id = u.id 
        AND t.article_id = a.id 
        AND c.thread_id = t.id 
        AND a.removed = 0 ';
        if(isset($categories)) {
            $catIndex = count($categories);
            $sql .= ' AND a.category = "'.$categories[0].'" ';
            if($catIndex > 1) {
                for ($i = 1; $i < $catIndex; $i++) {
                    $sql.=' OR a.category = "'.$categories[$i].'" '; }
            }
        }

        if($c)  { $sql .= ' AND a.titre like "%'.$discrim[0].'%" ';
            for($i = 1; $i < $c; $i++) 
                $sql .= ' OR a.titre like "%'.$discrim[0].'%" '; }
        if($contentToo)
            for($i = 0; $i++; $i < $c)
            $sql .= ' OR a.content like "%'.$discrim[0].'%" ';

            $sql .= ' GROUP BY a.id
            ORDER BY a.date_creation DESC ';
        
        $resultat = $conn->prepare($sql);
        $resultat->execute();

    return $resultat->fetchAll();
    return NULL;
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
