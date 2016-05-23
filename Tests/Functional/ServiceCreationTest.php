<?php

/**
 * This file is part of the "NFQ Bundles" package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nfq\BannerBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ServiceCreationTest
 * @package Nfq\BannerBundle\Tests\Functional
 */
class ServiceCreationTest extends WebTestCase
{
    /**
     * Tests if container is returned.
     */
    public function testGetContainer()
    {
        $container = self::createClient()->getKernel()->getContainer();
        $this->assertNotNull($container);
    }

    /**
     * Tests if service are created correctly.
     *
     * @param string $serviceId
     * @param string $instance
     *
     * @dataProvider getTestServiceCreateData
     */
    public function testServiceCreate($serviceId, $instance)
    {
        $container = self::createClient()->getKernel()->getContainer();
        $this->assertTrue($container->has($serviceId), sprintf('Service `%s` was not found in container', $serviceId));

        $service = $container->get($serviceId);
        $this->assertInstanceOf($instance, $service,
            sprintf('Invalid instance `%s` for service `%s`', $instance, $serviceId));
    }

    /**
     * Data provider for testServiceCreate().
     *
     * @return array[]
     */
    public function getTestServiceCreateData()
    {
        return [
            [
                'nfq_banner.generic_search',
                'Nfq\\BannerBundle\\Service\\Admin\\Search\\BannerSearch',
            ],
            [
                'nfq_banner.admin.banner_manager',
                'Nfq\\BannerBundle\\Service\\Admin\\BannerManager',
            ],
            [
                'nfq_banner.admin.service.banner_upload_manager',
                'Nfq\\BannerBundle\\Service\\Admin\\BannerUploadManager',
            ],
            [
                'nfq_banner.banner_manager',
                'Nfq\\BannerBundle\\Service\\BannerManager',
            ],
            [
                'nfq_banner.twig.banner_extension',
                'Nfq\\BannerBundle\\Twig\\BannerExtension',
            ],
            [
                'nfq_banner.notice_listener',
                'Nfq\\AdminBundle\\EventListener\\NoticeListener',
            ],
            [
                'nfq_banner.entity_listener.file_upload',
                'Nfq\\BannerBundle\\EventListener\\Doctrine\\FileUploadListener',
            ],
            [
                'nfq_banner.repository.banner',
                'Doctrine\\ORM\\EntityRepository',
            ],
            [
                'nfq_banner.admin_configure_menu_listener',
                'Nfq\\BannerBundle\\EventListener\\AdminMenuListener',
            ],
            [
                'validator.exact_image_dimensions',
                'Nfq\\BannerBundle\\Validator\\Constraint\\BannerDimensionsValidator',
            ],
        ];
    }
}
