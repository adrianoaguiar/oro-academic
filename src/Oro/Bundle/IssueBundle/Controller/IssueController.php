<?php

namespace Oro\Bundle\IssueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\IssueBundle\Entity\Issue;

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

    }

    /**
     * @Route("/delete/{id}", name="oro_issue_delete", requirements={"id"="\d+"})
     * @Template()
     */
    public function deleteAction($id)
    {

    }
}