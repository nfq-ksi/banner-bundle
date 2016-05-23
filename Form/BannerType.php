<?php

/**
 * This file is part of the "NFQ Bundles" package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nfq\BannerBundle\Form;

use Nfq\AdminBundle\Form\TranslatableType;
use Nfq\BannerBundle\Validator\Constraint\BannerDimensions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

/**
 * Class BannerType
 * @package Nfq\BannerBundle\Form
 */
class BannerType extends TranslatableType
{
    /**
     * @var array
     */
    private $bannerConfig;

    /**
     * @param array $bannerConfig
     */
    public function __construct(array $bannerConfig)
    {
        $this->bannerConfig = $bannerConfig;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function callBuildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('active', 'checkbox', [
                'required' => false,
            ])
            ->add('static', 'checkbox', [
                'required' => false,
                'attr' => [
                    'class' => 'is-static',
                ],
            ])
            ->add('place', 'choice', [
                'choices' => $this->getBannerPlaces(),
                'empty_value' => 'generic.choose',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('title', 'text', [
                    'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('text', 'textarea', [
                'required' => false,
                'attr' => [
                    'class' => 'tinymce'
                ],
            ])
            ->add('link', 'text', [
                'required' => false,
            ])
            ->add('startDate', 'datetime', [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'datepicker dt-start-date',
                ],
                'required' => false,
                'empty_value' => null
            ])
            ->add('endDate', 'datetime', [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'datepicker dt-end-date',
                ],
                'required' => false,
                'empty_value' => null
            ])
            ->add('file')
            ->add('sortPosition', 'integer', [
                'constraints' => [
                    new Range([
                        'min' => -100,
                        'max' => 100,
                    ]),
                ]
            ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function callSetDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Nfq\\BannerBundle\\Entity\\Banner',
            'constraints' => [
                new BannerDimensions()
            ]
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'banner';
    }

    /**
     * @return array
     */
    private function getBannerPlaces()
    {
        $places = [];

        if (!isset($this->bannerConfig['banner_places'])) {
            return $places;
        }

        foreach ($this->bannerConfig['banner_places'] as $placeKey => $bannerPlaces) {
            $placeTitle = $bannerPlaces['title'];

            if (isset($bannerPlaces['width'] ) && isset($bannerPlaces['height'])) {
                $placeTitle .= ' (' . $bannerPlaces['width'] . 'x' .  $bannerPlaces['height'] .')';
            }

            $places[$placeKey] = $placeTitle;
        }

        return $places;
    }
}
