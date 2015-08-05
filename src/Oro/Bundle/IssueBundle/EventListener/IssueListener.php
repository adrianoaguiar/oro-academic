<?php

namespace Oro\Bundle\IssueBundle\EventListener;

use Symfony\Component\Security\Core\SecurityContextInterface;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;

use Oro\Bundle\EntityConfigBundle\DependencyInjection\Utils\ServiceLink;
use Oro\Bundle\IssueBundle\Entity\Issue;

/**
 * Class IssueListener
 * @package Oro\Bundle\IssueBundle\EventListener
 */
class IssueListener
{
    /**
     * @var SecurityContextInterface
     */
    protected $securityContextLink;

    /**
     * @param ServiceLink $securityContextLink
     */
    public function __construct(ServiceLink $securityContextLink)
    {
        $this->securityContextLink = $securityContextLink;
    }

    /**
     * @return SecurityContextInterface
     */
    protected function getSecurityContext()
    {
        return $this->securityContextLink->getService();
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!($entity instanceof Issue)) {
            return;
        }

        /** @var Issue $entity */
        $user = $this->getSecurityContext()->getToken()->getUser();

        //add reporter as collaborator
        $entity->addCollaborator($user);

        //add assignee as collaborator
        $entity->addCollaborator($entity->getAssignee());
    }

    /**
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $entityManager = $args->getEntityManager();
        $unitOfWork = $entityManager->getUnitOfWork();

        $entities = array_merge(
            $unitOfWork->getScheduledEntityInsertions(),
            $unitOfWork->getScheduledEntityUpdates()
        );

        foreach ($entities as $entity) {
            if (!($entity instanceof Issue)) {
                continue;
            }

            $entity->addCollaborator($entity->getAssignee());
            $meta = $entityManager->getClassMetadata(get_class($entity));
            $unitOfWork->computeChangeSet($meta, $entity);
        }
    }
}
