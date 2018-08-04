<?php

namespace App\Repository;

use App\Entity\Thread;
use App\Entity\Comment;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function list(Thread $thread, $offset = 0, $limit = 0)
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.author', 'author')
            ->andWhere('c.thread = :thread')
            ->orderBy('c.date_creation', 'ASC')
            ->setParameter('thread', $thread)
            ->setMaxResults($limit)
            ->setFirstResult($offset * $limit)
            ->getQuery()->execute();

            return $qb;
    }

    public function getCommentPageCount(Thread $thread)
    {
        return ($this->createQueryBuilder('c')
        ->select('COUNT(c)')
        ->andWhere('c.thread = :thread')
        ->setParameter('thread', $thread)
         ->getQuery()
        ->getSingleScalarResult());
    }

//    /**
//     * @return Comment[] Returns an array of Comment objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
