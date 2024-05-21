<?php

use yii\db\Migration;

/**
 * Class m240521_115328_project_agent_to_user
 */
class m240521_115328_project_agent_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('project_agent', 'user');
        $this->dropIndex('project_agent_phone_idx', 'user');
        $this->dropIndex('project_agent_email_idx', 'user');
        $this->dropIndex('project_agent_telegram_idx', 'user');
        $this->createIndex(
            'user_phone_idx',
            'user',
            ['phone'],
            true,
        );
        $this->createIndex(
            'user_email_idx',
            'user',
            ['email'],
            true,
        );
        $this->createIndex(
            'user_telegram_idx',
            'user',
            ['telegram'],
            true,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('user', 'project_agent');
        $this->dropIndex('user_phone_idx', 'project_agent');
        $this->dropIndex('user_email_idx', 'project_agent');
        $this->dropIndex('user_telegram_idx', 'project_agent');
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
    }
}
