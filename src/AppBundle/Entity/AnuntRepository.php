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
    public function findAnunturi($search = null, $sortType = null)
    {
        $qb = $this->createQueryBuilder('a');

        if ($search) {
            $qb->where('a.title LIKE :search');
            $qb->setParameter('search', '%' . $search . '%');
        }

        switch ($sortType) {
            case "price-asc":
                $qb->orderBy('a.price', 'ASC');
                break;
            case "price-desc":
                $qb->orderBy('a.price', 'DESC');
                break;
            case "created-asc":
                $qb->orderBy('a.createdAt', 'ASC');
                break;
            default:
                $qb->orderBy('a.createdAt', 'DESC');
        }
        return $qb->getQuery()->getResult();
    }
}