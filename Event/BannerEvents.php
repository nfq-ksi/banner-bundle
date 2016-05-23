<?php

/**
 * This file is part of the "NFQ Bundles" package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nfq\BannerBundle\Event;

/**
 * Class BannerEvents
 * @package Nfq\BannerBundle\Event
 */
final class BannerEvents
{
    const BANNER_BEFORE_INSERT = 'banner.before_insert';
    const BANNER_AFTER_INSERT = 'banner.after_insert';
    const BANNER_BEFORE_SAVE = 'banner.before_save';
    const BANNER_AFTER_SAVE = 'banner.after_save';
    const BANNER_BEFORE_DELETE = 'banner.before_delete';
    const BANNER_AFTER_DELETE = 'banner.after_delete';
}
