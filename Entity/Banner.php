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
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Nfq\BannerBundle\Entity\Banner
 *
 * @ORM\Table(name="banner", indexes={@ORM\Index(name="place_idx", columns={"place"})})
 * @ORM\Entity(repositoryClass="Nfq\BannerBundle\Entity\BannerRepository")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\TranslationEntity(class="Nfq\BannerBundle\Entity\BannerTranslation")
 * @Assert\Callback("validate")
 */
class Banner implements Translatable
{
    /**
     * @var string $text
     * @Gedmo\Translatable
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    protected $text;

    /**
     * Variable to temporarily store path to old file
     *
     * @var string
     */
    private $tempImage;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string $place
     *
     * @ORM\Column(name="place", type="string", length=40)
     */
    private $place;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var string $link
     * @Gedmo\Translatable
     * @ORM\Column(name="link", type="text", nullable=true)
     */
    private $link;

    /**
     * @Assert\Image(maxSize="5242880", maxSizeMessage="banner.errors.file_too_large")
     */
    private $file;

    /**
     * @var \DateTime $startDate
     *
     * @ORM\Column(name="start_date", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime $endDate
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @Gedmo\Translatable
     * @var bool $active
     * @ORM\Column(name="active", type="boolean", options={"default":0})
     */
    private $active;

    /**
     * @Gedmo\Translatable
     * @var bool $static
     * @ORM\Column(name="static", type="boolean", options={"default":0})
     */
    private $static;

    /**
     * @var \DateTime $addedAt
     *
     * @ORM\Column(name="added_at", type="datetime", nullable=false)
     */
    private $addedAt;

    /**
     * @var \DateTime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var int $sortPosition
     * @ORM\Column(name="sort_position", type="smallint", nullable=true)
     */
    private $sortPosition;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;

    /**
     * @var array
     */
    private $resizeParams = null;

    /**
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        if (!$this->isStatic()) {
            if (!$this->startDate instanceof \DateTime) {
                $context->buildViolation('banner.errors.invalid_date')
                    ->atPath('startDate')
                    ->addViolation();

            }
            if (!$this->endDate instanceof \DateTime) {
                $context->buildViolation('banner.errors.invalid_date')
                    ->atPath('endDate')
                    ->addViolation();

            }
        } else {
            // If banner is set to static, reset banner dates.
            $this->startDate = $this->endDate = null;
        }
    }

    public function __toString()
    {
        return $this->getPlace();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());
        if (is_null($this->id)) {
            $this->setAddedAt(new \DateTime());
        }
    }

    /**
     * @return string
     */
    public function getTempFile()
    {
        return $this->tempImage;
    }

    /**
     *
     */
    public function resetTempFile()
    {
        $this->tempImage = null;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get place
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set place
     *
     * @param string $place
     *
     * @return $this
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get file
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set file
     *
     * @param UploadedFile $file
     *
     * @return $this
     */
    public function setFile($file)
    {
        if (isset($this->image)) {
            $this->tempImage = $this->image;
        }
        $this->file = $file;

        return $this;
    }

    /**
     * @return $this
     */
    public function resetFile()
    {
        $this->file = null;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return $this
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get start_date
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set start_date
     *
     * @param \DateTime $startDate
     *
     * @return $this
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get end_date
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set end_date
     *
     * @param \DateTime $endDate
     *
     * @return $this
     */
    public function setEndDate($endDate)
    {
        if ($endDate instanceof \DateTime) {
            $endDate->setTime(23, 59, 59);
        }
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive();
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     *
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getAddedAt()
    {
        return $this->addedAt;
    }

    /**
     * @param \DateTime $addedAt
     *
     * @return $this
     */
    public function setAddedAt($addedAt)
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getTextSimple()
    {
        $text = str_replace(['<p>','</p>'], '', $this->text);
        $text = str_replace('#LINK#', $this->link, $text);

        return $text;
    }

    /**
     * @param bool $json
     *
     * @return array|string
     */
    public function getGAnalyticsPromoData($json = false)
    {
        $data = [
            'id' => $this->getId(),
            'name' => $this->getTitle(),
            'creative' => 'Place: ' . $this->getPlace(),
            'position' => $this->getId(),
        ];

        return $json ? json_encode($data) : $data;
    }

    /**
     * @return array
     */
    public function getResizeParams()
    {
        return $this->resizeParams;
    }

    /**
     * @param array $resizeParams
     *
     * @return $this
     */
    public function setResizeParams(array $resizeParams)
    {
        $this->resizeParams = $resizeParams;

        return $this;
    }

    /**
     * @return int
     */
    public function getSortPosition()
    {
        return $this->sortPosition;
    }

    /**
     * @param int $sortPosition
     *
     * @return $this
     */
    public function setSortPosition($sortPosition)
    {
        $this->sortPosition = $sortPosition;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isStatic()
    {
        return $this->static;
    }

    /**
     * @param boolean $static
     *
     * @return Banner
     */
    public function setStatic($static)
    {
        $this->static = $static;

        return $this;
    }

    /**
     * @return bool
     */
    public function getStatic()
    {
        return $this->static;
    }
}
