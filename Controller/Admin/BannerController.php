<?php

/**
 * This file is part of the "NFQ Bundles" package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nfq\BannerBundle\Controller\Admin;

use Nfq\AdminBundle\Controller\Traits\CrudIndexController;
use Nfq\AdminBundle\Controller\Traits\TranslatableCRUDController;
use Nfq\AdminBundle\Service\FormManager;
use Nfq\BannerBundle\Entity\Banner;
use Nfq\BannerBundle\Form\BannerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class BannerController
 * @package Nfq\BannerBundle\Controller\Admin
 */
class BannerController extends Controller
{
    use TranslatableCRUDController;
    use CrudIndexController {
        indexAction as traitIndexAction;
    }

    /**
     * Lists all entities.
     *
     * @Template()
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        $response = $this->traitIndexAction($request);

        return $response + [
            'bannerConfig' => $this->getBannerConfig(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableEntityForLocale($id, $locale)
    {
        return $this->getAdminBannerManager()->getEditableEntity($id, $locale);
    }

    /**
     * {@inheritdoc}
     */
    protected function getIndexActionResultsArray(Request $request)
    {
        return $this->getAdminBannerManager()->getResults($request);
    }

    /**
     * @param Request $request
     * @param Banner|null $entity
     * @return RedirectResponse
     */
    protected function redirectToIndex(Request $request, $entity = null)
    {
        $redirectParams = $this->getRedirectToIndexParams($request, $entity);
        $redirectUri = $this->generateUrl('nfq_banner_list', $redirectParams->all());

        return $this->redirect($redirectUri);
    }

    /**
     * {@inheritdoc}
     */
    protected function getCreateFormAndEntity($locale)
    {
        $entity = new Banner();
        $entity->setLocale($locale);

        $formOptions = [
            'locale' => $locale,
        ];

        $uri = $this->generateUrl('nfq_banner_create');
        $form = $this
            ->getFormService()
            ->getCreateForm($uri, new BannerType($this->getBannerConfig()), $entity, $formOptions,
                FormManager::SUBMIT_STANDARD | FormManager::SUBMIT_CLOSE);

        return [$entity, $form];
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditDeleteForms($entity)
    {
        $id = $entity->getId();

        $formOptions = [
            'locale' => $entity->getLocale(),
        ];

        $uri = $this->generateUrl('nfq_banner_update', ['id' => $id]);
        $editForm = $this
            ->getFormService()->getEditForm($uri, new BannerType($this->getBannerConfig()), $entity, $formOptions,
                FormManager::SUBMIT_STANDARD | FormManager::SUBMIT_CLOSE);

        $deleteForm = $this->getDeleteForm($id);

        return [$editForm, $deleteForm];
    }

    /**
     * {@inheritdoc}
     */
    protected function getDeleteForm($id)
    {
        $uri = $this->generateUrl('nfq_banner_delete', ['id' => $id]);
        $deleteForm = $this->getFormService()->getDeleteForm($uri);

        return $deleteForm;
    }

    /**
     * @param $entity
     */
    protected function insertAfterCreateAction($entity)
    {
        $this->getAdminBannerManager()->insert($entity);
    }

    /**
     * {@inheritdoc}
     */
    protected function deleteAfterDeleteAction($entity)
    {
        $this->getAdminBannerManager()->delete($entity);
    }

    /**
     * @param Banner $entity
     */
    protected function saveAfterUpdateAction($entity)
    {
        $this->getAdminBannerManager()->save($entity);
    }

    /**
     * @return \Nfq\BannerBundle\Service\Admin\BannerManager
     */
    private function getAdminBannerManager()
    {
        return $this->get('nfq_banner.admin.banner_manager');
    }

    /**
     * @return mixed
     */
    private function getBannerConfig()
    {
        return $this->container->getParameter('nfq_banner');
    }
}
