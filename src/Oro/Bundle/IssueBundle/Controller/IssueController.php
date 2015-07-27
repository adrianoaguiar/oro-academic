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
     * @Acl(
     *      id="oro_issue_view",
     *      type="entity",
     *      class="OroIssueBundle:Issue",
     *      permission="VIEW"
     * )
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'entity_class' => $this->container->getParameter('oro_issue.entity.issue.class')
        );
    }
}