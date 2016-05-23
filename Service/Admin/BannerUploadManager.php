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

use Doctrine\ORM\Query;
use Nfq\BannerBundle\Entity\Banner;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BannerUploadManager
 * @package Nfq\BannerBundle\Service\Admin
 */
class BannerUploadManager
{
    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->resolveConfig($config);
    }

    /**
     * @param Banner $entity
     */
    public function upload(Banner $entity)
    {
        if (null === $entity->getFile()) {
            return;
        }

        $uploadDir = $this->getUploadPath($entity->getId());

        $entity->getFile()->move($uploadDir, $entity->getImage());

        $entity->resetFile();
    }

    /**
     * @param $id
     * @param $tempImage
     * @param $locale
     */
    public function removeFile($id, $tempImage, $locale)
    {
        //Do not delete temp image if locales are different
        if (strpos($tempImage, '_' . crc32($locale)) === false) {
            return;
        }

        $file = $this->getUploadPath($id, $tempImage);

        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * @param int $entityId
     */
    public function removeFiles($entityId)
    {
        $filesDir = $this->getUploadPath($entityId);

        if (is_dir($filesDir)) {
            $it = new \DirectoryIterator($filesDir);
            foreach ($it as $file) {
                if ($file->isDot()) {
                    continue;
                }

                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }

            rmdir($filesDir);
        }
    }

    /**
     * @param Banner|array $entity
     *
     * @return string|void
     */
    public function getWebPathForEntity($entity)
    {
        if ($entity instanceof Banner && $entity->getImage()) {
            return $this->getWebPath($entity->getId(), $entity->getImage());
        } elseif (is_array($entity)) {
            return $this->getWebPath($entity['id'], $entity['image']);
        }

        return '';
    }

    /**
     * @param Banner|array $entity
     *
     * @return string|void
     */
    public function getUploadPathForEntity($entity)
    {
        if ($entity instanceof Banner && $entity->getImage()) {
            return $this->getUploadPath($entity->getId(), $entity->getImage());
        } elseif (is_array($entity)) {
            return $this->getUploadPath($entity['id'], $entity['image']);
        }

        return '';
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param $config
     */
    private function resolveConfig($config)
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(['upload_absolute', 'upload_relative']);

        $this->config = $resolver->resolve($config);
    }

    /**
     * @param int    $id
     * @param string $image
     *
     * @return string
     */
    private function getWebPath($id, $image = '')
    {
        $_id = md5($id);

        return DIRECTORY_SEPARATOR . $this->config['upload_relative'] . $_id . DIRECTORY_SEPARATOR . $image;
    }

    /**
     * @param int    $id
     * @param string $image
     *
     * @return string
     */
    private function getUploadPath($id, $image = '')
    {
        $_id = md5($id);

        return $this->config['upload_absolute'] . $_id . DIRECTORY_SEPARATOR . $image;
    }
}
