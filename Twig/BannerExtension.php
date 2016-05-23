<?php

/**
 * This file is part of the "NFQ Bundles" package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nfq\BannerBundle\Twig;

use Nfq\BannerBundle\Service\Admin\BannerUploadManager;
use Nfq\BannerBundle\Service\BannerManager;

/**
 * Class BannerExtension
 * @package Nfq\BannerBundle\Twig
 */
class BannerExtension extends \Twig_Extension
{
    /**
     * @var BannerManager
     */
    private $manager;

    /**
     * @param BannerManager $manager
     */
    public function __construct(BannerManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'banner_list',
                [$this, 'getBannerList'],
                [
                    'needs_environment' => true,
                    'is_safe' => ['html'],
                ]
            ),
            new \Twig_SimpleFunction('banner_image_src', [$this, 'getBannerImageSrc']),
        ];
    }

    /**
     * @param \Twig_Environment $environment
     * @param string            $place
     * @param string            $locale
     *
     * @return array
     */
    public function getBannerList(\Twig_Environment $environment, $place, $locale = null)
    {
        if (empty($locale)) {
            $locale = $this->getRequestLocale($environment);
        }

        $placeConfig = $this->getBannerManager()->getConfig($place);
        $banners = $this->getBannerManager()->getBannersByPlace($place, $locale);

        return $environment->render(
            isset($placeConfig['template']) ? $placeConfig['template'] : 'NfqBannerBundle:Banner:viewList.html.twig',
            [
                'place' => $place,
                'banners' => $banners,
            ]
        );
    }

    /**
     * @param array $entity
     *
     * @return string
     */
    public function getBannerImageSrc($entity)
    {
        return $this->getUploadManager()->getWebPathForEntity($entity);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'banner_extension';
    }

    /**
     * @return BannerManager
     */
    private function getBannerManager()
    {
        return $this->manager;
    }

    /**
     * @return BannerUploadManager
     */
    private function getUploadManager()
    {
        return $this->getBannerManager()->getUploadManager();
    }

    /**
     * @param \Twig_Environment $environment
     *
     * @return string
     */
    private function getRequestLocale(\Twig_Environment $environment)
    {
        return $environment->getGlobals()['app']->getRequest()->getLocale();
    }
}
