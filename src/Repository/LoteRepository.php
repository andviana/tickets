<?php

namespace App\Repository;

use App\Entity\Evento;
use App\Entity\Lote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Lote>
 *
 * @method Lote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lote[]    findAll()
 * @method Lote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lote::class);
    }

//    /**
//     * @return Lote[] Returns an array of Lote objects
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

//    public function findOneBySomeField($value): ?Lote
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

   public function findByEvento(Evento $evento):array
    {
        return $this->createQueryBuilder('l')
            ->join('l.tickets','t',)
            ->join('t.produto','p')
            ->andWhere('t.evento = :evento')
            ->setParameter('evento', $evento)
            ->orderBy('p.denominacao', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
