<?php

namespace Oro\Bundle\IssueBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Oro\Bundle\IssueBundle\Entity\Issue;

/**
 * Class IssueType
 * @package Oro\Bundle\IssueBundle\Form\Type
 */
class IssueType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'code',
                'text',
                [
                    'required' => true,
                    'label' => 'oro.issue.grid.code.label'
                ]
            )
            ->add(
                'summary',
                'text',
                [
                    'required' => true,
                    'label' => 'oro.issue.grid.summary.label'
                ]
            )
            ->add(
                'description',
                'textarea',
                [
                    'required' => false,
                    'label' => 'oro.issue.grid.description.label'
                ]
            )
            ->add(
                'assignee',
                'oro_user_select',
                [
                    'required'      => true,
                    'label'         => 'oro.issue.grid.assignee.label',
                ]
            )
            ->add(
                'type',
                'choice',
                [
                    'label'   => 'oro.issue.grid.type.label',
                    'choices' => [
                        Issue::TYPE_TASK    => 'oro.issue.grid.type.' . Issue::TYPE_TASK,
                        Issue::TYPE_STORY   => 'oro.issue.grid.type.' . Issue::TYPE_STORY,
                        Issue::TYPE_SUBTASK => 'oro.issue.grid.type.' . Issue::TYPE_SUBTASK,
                        Issue::TYPE_BUG     => 'oro.issue.grid.type.' . Issue::TYPE_BUG,
                    ],
                    'required' => true
                ]
            )
            ->add(
                'priority',
                'entity',
                [
                    'label'    => 'oro.issue.grid.priority.label',
                    'class'    => 'Oro\Bundle\IssueBundle\Entity\IssuePriority',
                    'required' => true,
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository->createQueryBuilder('priority')->orderBy('priority.priority');
                    }
                ]
            )
            ->add(
                'resolution',
                'entity',
                [
                    'label'    => 'oro.issue.grid.resolution.label',
                    'class'    => 'Oro\Bundle\IssueBundle\Entity\IssueResolution',
                    'required' => true,
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository->createQueryBuilder('resolution')->orderBy('resolution.priority');
                    }
                ]
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Oro\Bundle\IssueBundle\Entity\Issue'
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'oro_issue_form_issue';
    }
}
