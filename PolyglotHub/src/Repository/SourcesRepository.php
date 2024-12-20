<?php

namespace App\Repository;

use App\Entity\Sources;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sources>
 */
class SourcesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sources::class);
    }

    //    /**
    //     * @return Sources[] Returns an array of Sources objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Sources
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function getSourceStats(): array
    {
        $qb = $this->createQueryBuilder('s')
            ->select('p.name as project, COUNT(s.id) as count')
            ->leftJoin('s.project', 'p')
            ->groupBy('p.name')
            ->getQuery();
        
        $results = $qb->getResult();
        
        return [
            'labels' => array_column($results, 'project'),
            'data' => array_column($results, 'count'),
        ];
    }

    public function getTranslationStats(): array
    {
        $qb = $this->createQueryBuilder('s')
            ->select('DATE_FORMAT(t.created_at, \'%Y-%m-%d\') as date, COUNT(t.id) as count')
            ->leftJoin('s.translations', 't')
            ->where('t.created_at IS NOT NULL')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->setMaxResults(6)
            ->getQuery();
        
        try {
            $results = $qb->getResult();

            return [
                'labels' => array_map(function($date) {
                    return (new \DateTime($date['date']))->format('d M Y');
                }, $results),
                'data' => array_column($results, 'count'),
            ];
        } catch (\Exception $e) {
            return [
                'labels' => [],
                'data' => [],
            ];
        }
    }
}
