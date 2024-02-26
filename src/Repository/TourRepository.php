<?php

namespace App\Repository;

use App\Entity\Tour;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tour>
 *
 * @method Tour|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tour|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tour[]    findAll()
 * @method Tour[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TourRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tour::class);
    }

//    /**
//     * @return Tour[] Returns an array of Tour objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Tour
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findToursBiggerDate($date, $idRuta){
        return $this->createQueryBuilder('tour')
                    ->andWhere('tour.ruta = :ruta')
                    ->setParameter('ruta', $idRuta)
                    ->andWhere('tour.fechaHora > :date')
                    ->setParameter('date', $date)
                    ->getQuery()
                    ->getResult();
    }

    public function findToursDate($date, $idRuta){
        return $this->createQueryBuilder('tour')
                    ->andWhere('tour.ruta = :ruta')
                    ->setParameter('ruta', $idRuta)
                    ->andWhere('SUBSTRING(tour.fechaHora, 1, 10) = :date')
                    ->setParameter('date', $date->format('Y-m-d'))
                    ->getQuery()
                    ->getResult();
    }

    public function findAvailableSeats($tourId){
        $qb = $this->createQueryBuilder('tour');
    
        $reservasTotales = $qb->select('SUM(reserva.nEntradas)')
                                 ->leftJoin('tour.reservas', 'reserva')
                                 ->where('tour.id = :tourId')
                                 ->setParameter('tourId', $tourId)
                                 ->getQuery()
                                 ->getResult();
    
        $tour = $this->find($tourId);
        $aforo = $tour->getRuta()->getAforo();
    
        $plazasDisponibles = $aforo - ($reservasTotales[0][1] ?? 0);
    
        return $plazasDisponibles;
    }

}
