<?php

use yii\db\Migration;

/**
 * Class m240414_171006_project_table
 */
class m240414_171006_all extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            'project_agent',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(255)->notNull(),
                'surname' => $this->string(255),
                'phone' => $this->string(15),
                'email' => $this->string(255),
                'telegram' => $this->string(32),
                'preferred_communication_method' => $this->integer()->notNull(),
                'created_at' => $this->timestamp(0)->notNull()->defaultExpression('now()'),
                'updated_at' => $this->timestamp(0)->notNull()->defaultExpression('now()'),
            ],
        );
        $this->createIndex(
            'project_agent_phone_idx',
            'project_agent',
            ['phone'],
            true,
        );
        $this->createIndex(
            'project_agent_email_idx',
            'project_agent',
            ['email'],
            true,
        );
        $this->createIndex(
            'project_agent_telegram_idx',
            'project_agent',
            ['telegram'],
            true,
        );

        $this->createTable(
            'project',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(255)->notNull(),
                'status' => $this->string(255)->notNull(),
                'description' => $this->text()->notNull(),
                'agent_id' => $this->integer()->notNull(),
                'created_at' => $this->timestamp(0)->notNull()->defaultExpression('now()'),
                'updated_at' => $this->timestamp(0)->notNull()->defaultExpression('now()'),
            ],
        );
        $this->addForeignKey(
            'project_agent_id_fkey',
            'project',
            ['agent_id'],
            'project_agent',
            ['id'],
            'SET NULL',
            'CASCADE',
        );

        $this->createTable(
            'entry',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(255)->notNull(),
                'summary' => $this->string(1024),
                'text' => $this->text()->notNull(),
                'status' => $this->string(255)->notNull(),
                'type' => $this->string(255)->notNull(),
                'project_id' => $this->integer()->notNull(),
                'published_at' => $this->timestamp(0),
                'created_at' => $this->timestamp(0)->notNull()->defaultExpression('now()'),
                'updated_at' => $this->timestamp(0)->notNull()->defaultExpression('now()'),
            ],
        );
        $this->addForeignKey(
            'entry_project_id_fkey',
            'entry',
            ['project_id'],
            'project',
            ['id'],
            'CASCADE',
            'CASCADE',
        );
        $this->createTable(
            'project_version',
            [
                'id' => $this->primaryKey(),
                'project_id' => $this->integer()->notNull(),
                'is_current' => $this->boolean()->notNull()->defaultValue(false),
                'name' => $this->string(255)->notNull(),
                'description' => $this->text()->notNull(),
                'files_url' => $this->string(1024)->notNull(),
                'active_since' => $this->timestamp(0)->notNull(),
                'created_at' => $this->timestamp(0)->notNull()->defaultExpression('now()'),
                'updated_at' => $this->timestamp(0)->notNull()->defaultExpression('now()'),
            ],
        );
        $this->createIndex(
            'project_version_project_id_name_idx',
            'project_version',
            ['project_id', 'name'],
            true,
        );
        $this->addForeignKey(
            'project_version_project_id_fkey',
            'project_version',
            ['project_id'],
            'project',
            ['id'],
            'CASCADE',
            'CASCADE',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('project_version');
        $this->dropTable('entry');
        $this->dropTable('project');
        $this->dropTable('project_agent');
    }
}
