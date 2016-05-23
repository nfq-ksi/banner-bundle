<?php

/**
 * This file is part of the "NFQ Bundles" package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nfq\BannerBundle\Service\Admin\Search;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Nfq\AdminBundle\Service\Generic\Search\GenericSearch;

/**
 * Class BannerSearch
 * @package Nfq\BannerBundle\Service\Admin
 */
class BannerSearch extends GenericSearch
{
    /**
     * {@inheritdoc}
     */
    protected function extendQuery(Request $request, QueryBuilder $queryBuilder)
    {
        // Start date
        $startDateFrom = $request->get('start_date_from');
        $startDateTo = $request->get('start_date_to');
        if (!empty($startDateFrom) && !empty($startDateTo)) {
            $queryBuilder->andWhere('search.startDate BETWEEN :startDateFrom AND :startDateTo');
            $queryBuilder->setParameter('startDateFrom', $startDateFrom);
            $queryBuilder->setParameter('startDateTo', $startDateTo);
        } elseif (!empty($startDateFrom)) {
            $queryBuilder->andWhere('search.startDate >= :startDateFrom');
            $queryBuilder->setParameter('startDateFrom', $startDateFrom);
        } elseif (!empty($startDateTo)) {
            $queryBuilder->andWhere('search.startDate <= :startDateTo');
            $queryBuilder->setParameter('startDateTo', $startDateTo);
        }

        // End date
        $endDateFrom = $request->get('end_date_from');
        $endDateTo = $request->get('end_date_to');
        if (!empty($endDateFrom) && !empty($endDateTo)) {
            $queryBuilder->andWhere('search.endDate BETWEEN :endDateFrom AND :endDateTo');
            $queryBuilder->setParameter('endDateFrom', $endDateFrom);
            $queryBuilder->setParameter('startDateTo', $endDateTo);
        } elseif (!empty($endDateFrom)) {
            $queryBuilder->andWhere('search.endDate >= :startDateFrom');
            $queryBuilder->setParameter('endDateFrom', $endDateFrom);
        } elseif (!empty($endDateTo)) {
            $queryBuilder->andWhere('search.endDate <= :endDateTo');
            $queryBuilder->setParameter('endDateTo', $endDateTo);
        }

        // Place
        $place = $request->get('place');
        if (!empty($place)) {
            $queryBuilder->andWhere('search.place = :place');
            $queryBuilder->setParameter('place', $place);
        }

        // Active
        $active = $request->get('active');
        if (!is_null($active) && (int)$active > -1) {
            $queryBuilder->andWhere('search.active = :active');
            $queryBuilder->setParameter('active', $active);
        }

        // Static
        $static = $request->get('static');
        if (!is_null($static) && (int)$static > -1) {
            $queryBuilder->andWhere('search.static = :static');
            $queryBuilder->setParameter('static', $static);
        }
    }

    /**
     * @inheritdoc
     */
    public function getRepository()
    {
        return $this->entityManager->getRepository('NfqBannerBundle:Banner');
    }
}
