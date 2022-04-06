<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\PropertySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\AST\Join;
use Doctrine\ORM\Query\Expr\Join as ExprJoin;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Article $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Article $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function countItems()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('COUNT(a)');
        $query = $qb->getQuery();
        $nb = $query->getResult()[0][1];
        settype($nb, 'integer');
        return $nb;
    }

    public function getItems(PropertySearch $search, $limit = 10)
    {
        $qb = $this->createQueryBuilder('a')
            ->join('a.category', 'c')
            ->addSelect('c');
        if ($search->getCategorie()) {
            $qb->andWhere('c.name = :val')
                ->setParameter('val', $search->getCategorie());
        }
        if ($search->getMin()) {
            $qb->andWhere('a.price >= :min')
                ->setParameter('min', $search->getMin());
        }

        if ($search->getMax()) {
            $qb->andWhere('a.price <= :max')
                ->setParameter('max', $search->getMax());
        }

        $qb->setMaxResults($limit)
            ->setFirstResult($search->getPage() * $limit);
        $query = $qb->getQuery();
        $data = $query->getResult();
        return [
            'paginator' => new Paginator($query),
            'data' => $data
        ];
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
