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
    public function findAnunturi($search = null,$sortType=null)
    {
        $qb = $this->createQueryBuilder('a');

        if ($search) {
            $qb->where('a.title LIKE :search');
            $qb->setParameter('search', '%' . $search . '%');
        }

        if($sortType=="price-asc")
        {
            $qb->orderBy('a.price','ASC');
        }
        else if($sortType=="price-desc")
        {
            $qb->orderBy('a.price','DESC');
        }
        else {
            $qb->orderBy('a.createdAt', 'DESC');
        }
        return $qb->getQuery()->getResult();
    }
}