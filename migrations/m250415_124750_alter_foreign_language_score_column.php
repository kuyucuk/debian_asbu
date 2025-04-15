<?php

use yii\db\Migration;

class m250415_124750_alter_foreign_language_score_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%user_self_assessment}}', 'foreign_language_score', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%user_self_assessment}}', 'foreign_language_score', $this->integer());
    }

    
}
