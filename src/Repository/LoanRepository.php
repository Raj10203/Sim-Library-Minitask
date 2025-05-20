<?php

namespace App\Repository;

use App\Entity\Loan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Loan>
 */
class LoanRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry  $registry,
        private Security $security
    )
    {
        parent::__construct($registry, Loan::class);
    }

    public function createLoanQueryBuilder(): Query
    {
        $query = $this->createQueryBuilder('loan')
            ->select('loan');

        if (!$this->security->isGranted('ROLE_ADMIN')) {
            $query = $query->andWhere('loan.user = :user')
                ->setParameter('user', $this->security->getUser());

        }
        $query = $query->andWhere('loan.returnedAt IS NULL');

        return $query
            ->orderBy('loan.dueAt', 'ASC')
            ->getQuery();
    }

    //    /**
    //     * @return Loan[] Returns an array of Loan objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Loan
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
