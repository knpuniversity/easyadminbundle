<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Genus;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

class GenusRepository extends EntityRepository
{
    /**
     * @return Genus[]
     */
    public function findAllPublishedOrderedByRecentlyActive()
    {
        return $this->createQueryBuilder('genus')
            ->andWhere('genus.isPublished = :isPublished')
            ->setParameter('isPublished', true)
            ->leftJoin('genus.notes', 'genus_note')
            ->orderBy('genus_note.createdAt', 'DESC')
//            ->leftJoin('genus.genusScientists', 'genusScientist')
//            ->addSelect('genusScientist')
            ->getQuery()
            ->execute();
    }

    /**
     * @return Genus[]
     */
    public function findAllExperts()
    {
        return $this->createQueryBuilder('genus')
            ->addCriteria(self::createExpertCriteria())
            ->getQuery()
            ->execute();
    }

    static public function createExpertCriteria()
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->gt('yearsStudied', 20))
            ->orderBy(['yearsStudied', 'DESC']);
    }

    public function getGenusCount()
    {
        return $this->createQueryBuilder('genus')
            ->select('COUNT(genus.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getPublishedGenusCount()
    {
        return $this->createQueryBuilder('genus')
            ->select('COUNT(genus.id)')
            ->where('genus.isPublished = :isPublished')
            ->setParameter('isPublished', true)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return Genus
     */
    public function findRandomGenus()
    {
        // very dirty way to get a "random" result - don't use in a real project!
        $results = $this->createQueryBuilder('genus')
            ->setMaxResults(10)
            ->getQuery()
            ->execute();

        return $results[array_rand($results)];
    }
}
