<?php
/**
 * Created by PhpStorm.
 * User: claudiu
 * Date: 11/11/15
 * Time: 4:54 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\EntityRepository;

class AnuntRepository extends EntityRepository
{
    public function findAnunturi($search = null)
    {
        $qb = $this->createQueryBuilder('a');

        if ($search) {
            $qb->where('a.title LIKE :search');
            $qb->setParameter('search', '%' . $search . '%');
        }

        $qb->orderBy('a.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }
}