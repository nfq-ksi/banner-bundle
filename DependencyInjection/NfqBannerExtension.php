<?php

/**
 * This file is part of the "NFQ Bundles" package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nfq\BannerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NfqBannerExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('nfq_banner', $config);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $this->mapConfig($container, $configs[0]);
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function mapConfig(ContainerBuilder $container, array $config)
    {
        $uploadDir = ltrim($config['upload_dir'], DIRECTORY_SEPARATOR);

        $config = [
            'upload_absolute' => $container->getParameter('kernel.root_dir') . '/../web/' . $uploadDir,
            'upload_relative' => $uploadDir,
        ];

        $container->setParameter('nfq_banner.config', $config);
    }
}
