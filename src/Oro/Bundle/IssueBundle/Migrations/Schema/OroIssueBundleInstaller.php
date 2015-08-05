<?php

namespace Oro\Bundle\IssueBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtension;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtensionAwareInterface;

/**
 * Class OroIssueBundleInstaller
 * @package Oro\Bundle\IssueBundle\Migrations\Schema
 */
class OroIssueBundleInstaller implements Installation, NoteExtensionAwareInterface
{
    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    /** @var NoteExtension */
    protected $noteExtension;

    /**
     * {@inheritdoc}
     */
    public function setNoteExtension(NoteExtension $noteExtension)
    {
        $this->noteExtension = $noteExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Tables generation **/
        $this->createOroIssueTable($schema);
        $this->createOroIssuePriorityTable($schema);
        $this->createOroIssueResolutionTable($schema);
        $this->createOroIssueCollaboratorsTable($schema);

        $this->noteExtension->addNoteAssociation($schema, 'oro_issue');
    }

    /**
     * Create oro_issue table
     *
     * @param Schema $schema
     */
    protected function createOroIssueTable(Schema $schema)
    {
        if (!$schema->hasTable('oro_issue')) {
            $table = $schema->createTable('oro_issue');
            $table->addColumn('id', 'integer', ['autoincrement' => true]);
            $table->addColumn('parent_id', 'integer', ['notnull' => false]);
            $table->addColumn('assignee_id', 'integer', ['notnull' => false]);
            $table->addColumn('priority_name', 'string', ['notnull' => false, 'length' => 255]);
            $table->addColumn('reporter_id', 'integer', ['notnull' => false]);
            $table->addColumn('summary', 'string', ['length' => 255]);
            $table->addColumn('code', 'string', ['length' => 30]);
            $table->addColumn('description', 'text', []);
            $table->addColumn('created_at', 'datetime', []);
            $table->addColumn('updated_at', 'datetime', []);
            $table->addColumn('type', 'string', ['length' => 255]);
            $table->addColumn('resolution_name', 'string', ['notnull' => false, 'length' => 32]);

            $table->setPrimaryKey(['id']);
            $table->addIndex(['priority_name'], 'IDX_DF0F9E3B965BD3DF', []);
            $table->addIndex(['reporter_id'], 'IDX_DF0F9E3BE1CFE6F5', []);
            $table->addIndex(['assignee_id'], 'IDX_DF0F9E3B59EC7D60', []);
            $table->addIndex(['parent_id'], 'IDX_DF0F9E3B727ACA70', []);
            $table->addIndex(['resolution_name'], 'IDX_DF0F9E3B8EEEA2E1', []);

            $table->addForeignKeyConstraint(
                $schema->getTable('oro_issue'),
                ['parent_id'],
                ['id'],
                ['onDelete' => 'CASCADE', 'onUpdate' => null]
            );
            $table->addForeignKeyConstraint(
                $schema->getTable('oro_user'),
                ['assignee_id'],
                ['id'],
                ['onDelete' => 'SET NULL', 'onUpdate' => null]
            );
            $table->addForeignKeyConstraint(
                $schema->getTable('oro_user'),
                ['reporter_id'],
                ['id'],
                ['onDelete' => 'SET NULL', 'onUpdate' => null]
            );
        }
    }

    /**
     * Create oro_issue_priority table
     *
     * @param Schema $schema
     */
    protected function createOroIssuePriorityTable(Schema $schema)
    {
        if (!$schema->hasTable('oro_issue_priority')) {
            $table = $schema->createTable('oro_issue_priority');
            $table->addColumn('name', 'string', ['length' => 32]);
            $table->addColumn('label', 'string', ['length' => 255]);
            $table->addColumn('priority', 'integer', []);
            $table->setPrimaryKey(['name']);
            $table->addUniqueIndex(['label'], 'UNIQ_CF28BF98EA750E8');

            $schema->getTable('oro_issue')->addForeignKeyConstraint(
                $schema->getTable('oro_issue_priority'),
                ['priority_name'],
                ['name'],
                ['onDelete' => 'SET NULL', 'onUpdate' => null]
            );
        }
    }

    /**
     * Create oro_issue_resolution table
     *
     * @param Schema $schema
     */
    protected function createOroIssueResolutionTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issue_resolution');
        $table->addColumn('name', 'string', ['length' => 32]);
        $table->addColumn('label', 'string', ['length' => 255]);
        $table->addColumn('priority', 'integer', []);
        $table->setPrimaryKey(['name']);
        $table->addUniqueIndex(['label'], 'UNIQ_4A352091EA750E8');

        $schema->getTable('oro_issue')->addForeignKeyConstraint(
            $table,
            ['resolution_name'],
            ['name'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
    }

    /**
     * Create oro_issue_collaborators table
     *
     * @param Schema $schema
     */
    protected function createOroIssueCollaboratorsTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issue_collaborators');
        $table->addColumn('issue_id', 'integer', []);
        $table->addColumn('user_id', 'integer', []);
        $table->setPrimaryKey(['issue_id', 'user_id']);
        $table->addIndex(['issue_id'], 'IDX_9DBAC525E7AA58C', []);
        $table->addIndex(['user_id'], 'IDX_9DBAC52A76ED395', []);

        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['user_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue'),
            ['issue_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }
}
