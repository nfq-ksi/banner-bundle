<?php

/**
 * This file is part of the "NFQ Bundles" package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nfq\BannerBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Class BannerDimensions
 * @package Nfq\BannerBundle\Validator\Constraint
 * @Annotation
 * @Target({"CLASS"})
 */
class BannerDimensions extends Constraint
{
    public $width;
    public $height;

    public $widthMessage = 'Image width: {{ width }} does not match expected: {{ expected_width }}';
    public $heightMessage = 'Image height: {{ height }} does not match expected: {{ expected_height }}';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'nfq_banner_dimmensions_validator';
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
