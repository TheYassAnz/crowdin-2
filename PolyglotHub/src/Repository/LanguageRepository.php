<?php

namespace App\Repository;

use App\Entity\Language;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Language>
 */
class LanguageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Language::class);
    }

    public function getLanguageStats(): array
    {
        try {
            $qb = $this->createQueryBuilder('l')
                ->select('l.name as language, COUNT(s.id) as count')
                ->leftJoin('App\Entity\Sources', 's', 'WITH', 's.target_language = l.id')
                ->groupBy('l.name')
                ->orderBy('count', 'DESC')
                ->getQuery();
            
            $results = $qb->getResult();
            
            return [
                'labels' => array_column($results, 'language'),
                'data' => array_column($results, 'count'),
            ];
        } catch (\Exception $e) {
            return [
                'labels' => [],
                'data' => [],
            ];
        }
    }

    //    /**
    //     * @return Language[] Returns an array of Language objects
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

    //    public function findOneBySomeField($value): ?Language
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
