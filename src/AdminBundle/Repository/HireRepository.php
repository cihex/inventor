<?php
namespace AdminBundle\Repository;

use AdminBundle\Entity\Exhibit;
use AdminBundle\Entity\Hire;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

/**
 * HireRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HireRepository extends EntityRepository
{
    /**
     * @param Exhibit $exhibit
     * @return Hire[]|null
     */
    public function getCurrentHireByExhibit(Exhibit $exhibit)
    {
        $repository = $this->getEntityManager()->getRepository(Hire::class);
        return $repository->createQueryBuilder('hire')
            ->innerJoin('hire.exhibits', 'exhibits')
            ->where('exhibits.id = :exhibit_id')
            ->setParameter('exhibit_id', $exhibit->getId())
            ->getQuery()->getOneOrNullResult();
    }

}