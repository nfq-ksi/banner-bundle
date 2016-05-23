<?php

/**
 * This file is part of the "NFQ Bundles" package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nfq\BannerBundle\EventListener\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Nfq\BannerBundle\Entity\Banner;
use Nfq\BannerBundle\Service\Admin\BannerUploadManager;

/**
 * Class FileUploadListener
 * @package Nfq\BannerBundle\EventListener\Doctrine
 */
class FileUploadListener
{
    /**
     * @var BannerUploadManager
     */
    private $uploadManager;

    /**
     * @var int
     */
    private $entityId;

    /**
     * @param BannerUploadManager $uploadManager
     */
    public function __construct(BannerUploadManager $uploadManager)
    {
        $this->uploadManager = $uploadManager;
    }

    /**
     * @param Banner             $entity
     * @param LifecycleEventArgs $event
     */
    public function postPersist(Banner $entity, LifecycleEventArgs $event)
    {
        $this->uploadManager->upload($entity);
    }

    /**
     * @param Banner             $entity
     * @param LifecycleEventArgs $event
     */
    public function postUpdate(Banner $entity, LifecycleEventArgs $event)
    {
        $this->uploadManager->upload($entity);
    }

    /**
     * @param Banner             $entity
     * @param PreFlushEventArgs $event
     */
    public function preFlush(Banner $entity, PreFlushEventArgs $event)
    {
        $this->setImage($entity);
        $this->removeExistingImage($entity);
    }

    /**
     * @param Banner             $entity
     * @param LifecycleEventArgs $event
     */
    public function preRemove(Banner $entity, LifecycleEventArgs $event)
    {
        $this->entityId = $entity->getId();
    }

    /**
     * @param Banner             $entity
     * @param LifecycleEventArgs $event
     */
    public function postRemove(Banner $entity, LifecycleEventArgs $event)
    {
        $this->uploadManager->removeFiles($this->entityId);
    }

    /**
     * @param Banner $entity
     */
    private function setImage(Banner $entity)
    {
        if (null !== $entity->getFile()) {
            $filename = sha1(uniqid(mt_rand(), true)) . '_' . crc32($entity->getLocale());
            $entity->setImage($filename . '.' . $entity->getFile()->guessExtension());
        }
    }

    /**
     * @param Banner $entity
     */
    private function removeExistingImage(Banner $entity)
    {
        if (null !== $entity->getTempFile()) {
            $this->uploadManager->removeFile($entity->getId(), $entity->getTempFile(), $entity->getLocale());
            $entity->resetTempFile();
        }
    }
}
