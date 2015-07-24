<?php

namespace Oro\Bundle\IssueBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * Class OroIssueBundleInstaller
 * @package Oro\Bundle\IssueBundle\Migrations\Schema
 */
class OroIssueBundleInstaller implements Installation
{
    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Tables generation **/
        $this->createOroIssueTable($schema);
        $this->createOroIssuePriorityTable($schema);
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
            $table->addColumn('priority_name', 'string', ['notnull' => false, 'length' => 32]);
            $table->addColumn('reporter_id', 'integer', ['notnull' => false]);
            $table->addColumn('summary', 'string', ['length' => 255]);
            $table->addColumn('code', 'string', ['length' => 30]);
            $table->addColumn('description', 'text', []);
            $table->addColumn('createdAt', 'datetime', []);
            $table->addColumn('updatedAt', 'datetime', []);
            $table->addColumn('issue_type', 'string', ['length' => 255]);
            $table->setPrimaryKey(['id']);
            $table->addIndex(['priority_name'], 'IDX_DF0F9E3B965BD3DF', []);
            $table->addIndex(['reporter_id'], 'IDX_DF0F9E3BE1CFE6F5', []);
            $table->addIndex(['assignee_id'], 'IDX_DF0F9E3B59EC7D60', []);
            $table->addIndex(['parent_id'], 'IDX_DF0F9E3B727ACA70', []);

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
}
