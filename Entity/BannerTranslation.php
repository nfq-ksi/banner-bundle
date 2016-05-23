<?php

/**
 * This file is part of the "NFQ Bundles" package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nfq\BannerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;

/**
 * @ORM\Table(
 *      name="banner_translations",
 *      indexes={
 *          @ORM\Index(name="banner_lookup_idx", columns={"locale", "objectClass", "foreignKey"})
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="banner_unique_idx", columns={"locale", "objectClass", "field", "foreignKey"})
 *      }
 * )
 * @ORM\Entity(repositoryClass="Gedmo\Translatable\Entity\Repository\TranslationRepository")
 */
class BannerTranslation extends AbstractTranslation
{
    /**
     * @var string $locale
     *
     * @ORM\Column(name="locale", length=5, nullable=false, options={"fixed": true, "collation":"ascii_bin"})
     */
    protected $locale;

    /**
     * @var string $objectClass
     *
     * @ORM\Column(name="objectClass", length=150, nullable=false, options={"collation":"ascii_bin"})
     */
    protected $objectClass;

    /**
     * @var string $field
     *
     * @ORM\Column(name="field", length=32, nullable=false, options={"collation":"ascii_bin"})
     */
    protected $field;

    /**
     * @var integer $foreignKey
     *
     * @ORM\Column(name="foreignKey", type="integer", length=11)
     */
    protected $foreignKey;
}
