<?php

use yii\db\Migration;

/**
 * Class m240520_170623_project_agent_avatar
 */
class m240520_170623_project_agent_avatar extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('project_agent', 'avatar', $this->string(2048));
        $this->addColumn('project', 'logo', $this->string(2048)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('project_agent', 'avatar');
        $this->dropColumn('project', 'logo');
    }
}
