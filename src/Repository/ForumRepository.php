<?php

namespace App\Repository;

use App\Entity\Forum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\Tests\Compiler\PrivateConstructor;

/**
 * @method Forum|null find($id, $lockMode = null, $lockVersion = null)
 * @method Forum|null findOneBy(array $criteria, array $orderBy = null)
 * @method Forum[]    findAll()
 * @method Forum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumRepository extends ServiceEntityRepository
{
    private $root;
    private $news;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Forum::class);
    }

    public function rootForum()
    {
        if(isset($root)) return $root;
        $root = $this->findOneBy(['isRootForum' => 1]);
        return $root;
    }

    public function newsForum()
    {
        if(isset($news)) return $news;
        $news = $this->findOneBy(['isNewsForum' => 1]);
        return $news;
    }

//    /**
//     * @return Forum[] Returns an array of Forum objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Forum
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
