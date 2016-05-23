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

use Nfq\BannerBundle\Entity\Banner;
use Nfq\BannerBundle\Service\BannerManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class BannerDimensionsValidator
 * @package Nfq\BannerBundle\Validator\Constraint
 */
class BannerDimensionsValidator extends ConstraintValidator
{
    /**
     * @var BannerManager
     */
    private $manager;

    /**
     * @inheritDoc
     */
    function __construct(BannerManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Checks if image file exact dimensions
     *
     * @param mixed      $entity The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($entity, Constraint $constraint)
    {
        if (!$constraint instanceof BannerDimensions) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\BannerDimensions');
        }
        if (!$entity instanceof Banner) {
            throw new UnexpectedTypeException($entity, 'Nfq\BannerBundle\Entity\Banner');
        }
        if (!$entity->getFile() instanceof UploadedFile) {
            return;
        }

        $this->buildConstraint($entity, $constraint);

        $size = @getimagesize($entity->getFile()->getRealPath());
        $width = $size[0];
        $height = $size[1];
        if ($constraint->width) {
            if (!ctype_digit((string)$constraint->width)) {
                throw new ConstraintDefinitionException(sprintf('"%s" is not a valid width', $constraint->width));
            }
            if ($width != $constraint->width) {
                if ($this->context instanceof ExecutionContextInterface) {
                    $this->context->buildViolation($constraint->widthMessage)->atPath('image')->setParameter(
                        '{{ width }}',
                        $width
                    )->setParameter('{{ expected_width }}', $constraint->width)->addViolation();
                } else {
                    $this->buildViolation($constraint->widthMessage)->setParameter('{{ width }}', $width)->setParameter(
                        '{{ expected_width }}',
                        $constraint->width
                    )->addViolation();
                }
            }
        }
        if ($constraint->height) {
            if (!ctype_digit((string)$constraint->height)) {
                throw new ConstraintDefinitionException(sprintf('"%s" is not a valid height', $constraint->height));
            }
            if ($height != $constraint->height) {
                if ($this->context instanceof ExecutionContextInterface) {
                    $this->context->buildViolation($constraint->heightMessage)->setParameter(
                        '{{ height }}',
                        $height
                    )->setParameter('{{ expected_height }}', $constraint->height)->addViolation();
                } else {
                    $this->buildViolation($constraint->heightMessage)->setParameter(
                        '{{ height }}',
                        $height
                    )->setParameter(
                        '{{ expected_height }}',
                        $constraint->height
                    )->addViolation();
                }
            }
        }
    }

    /**
     * @param Banner           $entity
     * @param BannerDimensions $constraint
     */
    private function buildConstraint($entity, $constraint)
    {
        if ($placeConfig = $this->manager->getConfig($entity->getPlace())) {
            $constraint->width = isset($placeConfig['width']) ? $placeConfig['width'] : null;
            $constraint->height = isset($placeConfig['height']) ? $placeConfig['height'] : null;
        };
    }
}
