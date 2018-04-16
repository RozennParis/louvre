<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
/**
 * BookingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookingRepository extends EntityRepository
{
    public function getClientBooking($id)
    {

        $qb = $this
            ->createQueryBuilder('b')
            ->innerJoin('b.tickets', 't')
            ->addSelect('t')
            ->where('b.id = :id')
            ->setParameter('id',$id)
        ;

        return $qb
            ->getQuery()
            ->getSingleResult();
    }
}
