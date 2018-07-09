<?php

namespace AdminBundle\Repository;

use AdminBundle\Entity\Exhibit;
use Doctrine\ORM\EntityRepository;

/**
 * ExhibitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ExhibitRepository extends EntityRepository
{
    /**
     * @return Exhibit
     */
    public function getLastExhibit()
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('exhibit')
            ->from($this->getEntityName(), 'exhibit')
            ->orderBy('exhibit.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $pattern
     * @return array
     */
    public function findByPattern($pattern): array
    {
        $repository = $this->getEntityManager()->getRepository(Exhibit::class);
        return $repository->createQueryBuilder('exhibit')
            ->where('exhibit.name LIKE :pattern')
            ->orWhere('exhibit.id = :exhibitId')
            ->setParameter('pattern', '%' . $pattern . '%')
            ->setParameter('exhibitId', (int)$pattern)
            ->getQuery()
            ->getResult();
    }
}
