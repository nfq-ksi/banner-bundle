<?php

/**
 * This file is part of the "NFQ Bundles" package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nfq\BannerBundle\Service\Admin;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectRepository;
use Nfq\BannerBundle\Entity\Banner;
use Nfq\BannerBundle\Entity\BannerRepository;
use Nfq\BannerBundle\Event\BannerEvents;
use Nfq\AdminBundle\Service\Admin\AbstractAdminManager;

/**
 * Class BannerManager
 * @package Nfq\BannerBundle\Service
 */
class BannerManager extends AbstractAdminManager
{
    /**
     * @var BannerRepository
     */
    private $repository;

    /**
     * @param BannerRepository|ObjectRepository $repository
     */
    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Banner $entity
     * @param string $beforeEventName
     * @param string $afterEventName
     * @return mixed
     */
    public function delete(
        $entity,
        $beforeEventName = BannerEvents::BANNER_BEFORE_DELETE,
        $afterEventName = BannerEvents::BANNER_AFTER_DELETE
    ) {
        return parent::delete($entity, $beforeEventName, $afterEventName);
    }

    /**
     * @param Banner $entity
     * @param string $beforeEventName
     * @param string $afterEventName
     * @return mixed
     */
    public function insert(
        $entity,
        $beforeEventName = BannerEvents::BANNER_BEFORE_INSERT,
        $afterEventName = BannerEvents::BANNER_AFTER_INSERT
    ) {
        return parent::insert($entity, $beforeEventName, $afterEventName);
    }

    /**
     * @param Banner $entity
     * @param string $beforeEventName
     * @param string $afterEventName
     * @return mixed
     */
    public function save(
        $entity,
        $beforeEventName = BannerEvents::BANNER_BEFORE_SAVE,
        $afterEventName = BannerEvents::BANNER_AFTER_SAVE
    ) {
        return parent::save($entity, $beforeEventName, $afterEventName);
    }

    /**
     * @param int $id
     * @param string $locale
     * @return Banner
     */
    public function getEditableEntity($id, $locale)
    {
        return $this->repository->getEditableEntity($id, $locale);
    }
}
