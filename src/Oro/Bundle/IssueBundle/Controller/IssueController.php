<?php

namespace Oro\Bundle\IssueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\IssueBundle\Entity\Issue;

/**
 * Class IssueController
 * @package Oro\Bundle\IssueBundle\Controller
 */
class IssueController extends Controller
{
    /**
     * @Route(name="oro_issue_index")
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'entity_class' => $this->container->getParameter('oro_issue.entity.issue.class')
        );
    }

    /**
     * @Route("/view/{id}", name="oro_issue_view", requirements={"id"="\d+"})
     * @Template
     *
     * @param Issue $issue
     * @return array
     */
    public function viewAction(Issue $issue)
    {
        return array('entity' => $issue);
    }

    /**
     * @Route("/create", name="oro_issue_create")
     * @Template("OroIssueBundle:Issue:update.html.twig")
     */
    public function createAction()
    {
        $userId = $this->getRequest()->get('userId');

        $entity = new Issue();

        if ($userId) {
            $user = $this->getDoctrine()->getRepository('OroUserBundle:User')->find($userId);
            if (!$user) {
                throw new NotFoundHttpException(sprintf('User with ID %s is not found', $user));
            }
            $entity->setReporter($user);
        } elseif ($reporter = $this->getUser()) {
            $entity->setReporter($reporter);
        }

        $formAction = $this->get('oro_entity.routing_helper')
            ->generateUrlByRequest('oro_issue_create', $this->getRequest());

        return $this->update($entity, $formAction);
    }

    /**
     * @Route("/update/{id}", name="oro_issue_update", requirements={"id"="\d+"})
     * @Template()
     *
     * @param Issue $entity
     * @return array
     */
    public function updateAction(Issue $entity)
    {
        $formAction = $this->get('router')->generate('oro_issue_update', ['id' => $entity->getId()]);

        return $this->update($entity, $formAction);
    }

    /**
     * @Route("/delete/{id}", name="oro_issue_delete", requirements={"id"="\d+"})
     * @Template()
     */
    public function deleteAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $entity = $manager->find('OroIssueBundle:Issue', $id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add(
                'error',
                $this->get('translator')->trans('oro.issue.controller.notfound.message')
            );

            return $this->redirect($this->generateUrl('oro_issue_index'));
        }

        $manager->remove($entity);
        $manager->flush();

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->get('translator')->trans('oro.issue.controller.deleted.message')
        );

        return $this->redirect($this->generateUrl('oro_issue_index'));
    }

    /**
     * @return IssueType
     */
    protected function getFormType()
    {
        return $this->get('oro_issue.form.type.issue');
    }

    protected function update(Issue $entity, $formAction)
    {
        $saved = false;

        if ($this->get('oro_issue.form.handler.issue')->process($entity)) {
            if (!$this->getRequest()->get('_widgetContainer')) {
                $this->get('session')->getFlashBag()->add(
                    'success',
                    $this->get('translator')->trans('oro.issue.controller.saved.message')
                );

                return $this->get('oro_ui.router')->redirectAfterSave(
                    ['route' => 'oro_issue_update', 'parameters' => ['id' => $entity->getId()]],
                    ['route' => 'oro_issue_index'],
                    $entity
                );
            }
            $saved = true;
        }

        return array(
            'entity'     => $entity,
            'saved'      => $saved,
            'form'       => $this->createForm($this->getFormType(), $entity)->createView(),
            'formAction' => $formAction,
        );
    }
}