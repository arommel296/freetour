<?php

namespace App\Repository;

use App\Entity\Ruta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ruta>
 *
 * @method Ruta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ruta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ruta[]    findAll()
 * @method Ruta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RutaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ruta::class);
    }

//    /**
//     * @return Ruta[] Returns an array of Ruta objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ruta
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findMejoresRutas(): array
    {
        // return $this->createQueryBuilder('v')
        $a = $this->createQueryBuilder('r');

        $a->select('r.id AS ruta_id, AVG(v.valor) AS media') 
            ->join('r.tours', 't')
            ->join('t.reservas', 're')
            ->join('re.valoraciones', 'v')
            ->groupBy('r.id')
            ->orderBy('media', 'DESC');

        return $a->getQuery()->getResult();
    }


    public function buscarfechaenrango($fechaBusqueda)
    {
        $qb = $this->createQueryBuilder('ruta');
        return $qb->select('ruta')
        ->from(Ruta::class, 'ruta')
        ->where(

            $qb->expr()->andX(
                $qb->expr()->lte('ruta.fecha_ini', ':fechaBusqueda'),
                $qb->expr()->gte('ruta.fecha_fin', ':fechaBusqueda')
            )
        )
        ->setParameter('fechaBusqueda', $fechaBusqueda)
        ->getQuery()
        ->getResult();
        ;
    }

    public function findByItemLocalidad($localidad): array
    {
        return $this->createQueryBuilder('r')
                    ->join('r.item', 'i') 
                    ->join('i.localidad', 'l') 
                    ->andWhere("l.nombre LIKE :localidad")
                    ->setParameter('localidad', $localidad)
                    ->getQuery()
                    ->getResult()
        ;
    }

}
