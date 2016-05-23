<?php

/**
 * This file is part of the "NFQ Bundles" package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nfq\BannerBundle\Service;

use Doctrine\Common\Persistence\ObjectRepository;
use Nfq\BannerBundle\Entity\Banner;
use Nfq\BannerBundle\Entity\BannerRepository;
use Nfq\BannerBundle\Service\Admin\BannerUploadManager;

/**
 * Class BannerManager
 * @package Nfq\BannerBundle\Service
 */
class BannerManager
{
    /**
     * @var ObjectRepository|BannerRepository
     */
    private $repository;

    /**
     * @var BannerUploadManager
     */
    private $manager;

    /**
     * @var array
     */
    private $bannerConfig;

    /**
     * @param ObjectRepository    $repository
     * @param BannerUploadManager $manager
     * @param array               $bannerConfig
     */
    public function __construct(ObjectRepository $repository, BannerUploadManager $manager, array $bannerConfig)
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->bannerConfig = $bannerConfig;
    }

    /**
     * @param string $place
     * @param string $locale
     *
     * @return array
     */
    public function getBannersByPlace($place, $locale)
    {
        $banners = [];
        $bannersDb = $this->repository->findByPlace($place, $locale);

        /**
         * @var Banner $banner
         */
        foreach ($bannersDb as $banner) {
            $imageFile = $this->getUploadManager()->getUploadPathForEntity($banner);
            if (!is_dir($imageFile) && file_exists($imageFile)) {
                $this->addResizingParams($place, $imageFile, $banner);
                $banners[] = $banner;
            }
        }

        return $banners;
    }

    /**
     * @return BannerUploadManager
     */
    public function getUploadManager()
    {
        return $this->manager;
    }

    /**
     * @param string|null $place
     * @return array
     */
    public function getConfig($place = null)
    {
        if (is_null($place)) {
            return $this->bannerConfig;
        }

        if (isset($this->bannerConfig['banner_places'][$place])) {
            return $this->bannerConfig['banner_places'][$place];
        }

        return [];
    }

    /**
     * @param string $place
     * @param string $imageFile
     * @param Banner $banner
     */
    private function addResizingParams($place, $imageFile, Banner $banner)
    {
        if (!isset($this->bannerConfig['banner_places'][$place])) {
            return;
        }

        $placeConfig = $this->bannerConfig['banner_places'][$place];
        $imageSize = getimagesize($imageFile);

        if ($this->needsResizing($placeConfig, $imageSize)) {
            $banner->setResizeParams(
                [
                    'width' => $placeConfig['width'],
                    'height' => $placeConfig['height'],
                ]
            );
        }
    }

    /**
     * @param array $placeConfig
     * @param array $imageSize
     *
     * @return bool
     */
    private function needsResizing(array $placeConfig, array $imageSize)
    {
        return $imageSize[0] > $placeConfig['width'] || $imageSize[1] > $placeConfig['height'];
    }
}
