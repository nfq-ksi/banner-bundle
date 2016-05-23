<?php

/**
 * This file is part of the "NFQ Bundles" package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nfq\BannerBundle\Entity;

use Nfq\AdminBundle\Doctrine\ORM\EntityRepository;

/**
 * Class BannerRepository
 * @package Nfq\BannerBundle\Entity
 */
class BannerRepository extends EntityRepository
{
    /**
     * Get banner by id with translated content.
     *
     * @param int $id
     * @param string $locale
     * @return null|Banner
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getEditableEntity($id, $locale)
    {
        $query = $this->getTranslatableQueryByCriteria(['id' => $id], $locale, false);

        //This line fixes issue with same translation rendered for different locale in editing popup
        $query->useQueryCache(false);

        return $query->getOneOrNullResult();
    }

    /**
     * @param string $place
     * @return Banner
     */
    public function findOneRandom($place)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select('b')
            ->from('NfqBannerBundle:Banner', 'b')
            ->where('b.place = :place')
            ->andWhere('b.startDate <= :now OR b.startDate IS NULL')
            ->andWhere('b.endDate >= :now OR b.endDate IS NULL')
            ->setParameters([
                'place' => $place,
                ':now' => new \DateTime()
            ]);

        $result = $qb->getQuery()->execute();

        if (count($result) > 1) {
            $entities = [];

            foreach ($result as $item) {
                $entities[] = $item;
            }

            $entity = $entities[array_rand($entities)];
        } else {
            $entity = current($result);
        }

        return $entity;
    }

    /**
     * @param string $place
     * @param string $locale
     * @param string $sortBy
     * @param string $sortOrder
     *
     * @return Banner
     */
    public function findByPlace($place, $locale, $sortBy = 'b.sortPosition', $sortOrder = 'ASC')
    {
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select('b')
            ->from('NfqBannerBundle:Banner', 'b')
            ->where('b.place = :place')
            ->andWhere('b.active = 1')
            ->andWhere($qb->expr()->orX(
                $qb->expr()->andX(
                    $qb->expr()->andX('b.startDate <= :now OR b.startDate IS NULL'),
                    $qb->expr()->andX('b.endDate >= :now OR b.endDate IS NULL')
                ),
                $qb->expr()->andX('b.static = 1')
            ))
            ->orderBy($sortBy, $sortOrder)
            ->addOrderBy('b.static', 'ASC')
            ->setParameters([
                ':place' => $place,
                ':now' => new \DateTime()
            ]);

        $query = $qb->getQuery();
        $this->setTranslatableHints($query, $locale, false);

        return $query->getResult();
    }
}
