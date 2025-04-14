<?php

use yii\db\Migration;

class m250325_110130_user_performance_score_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_performance_score}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'service_year_score' => $this->integer()->defaultValue(0),
            'education_level_score' => $this->integer()->defaultValue(0),
            'foreign_language_score' => $this->integer()->defaultValue(0),
            'internal_training_score' => $this->integer()->defaultValue(0),
            'external_training_score' => $this->integer()->defaultValue(0),
            'committee_participation_score' => $this->integer()->defaultValue(0),
            'education_given_score' => $this->integer()->defaultValue(0),
            'improvement_activity_score' => $this->integer()->defaultValue(0),
            'internal_meeting_score' => $this->integer()->defaultValue(0),
            'total_self_score' => $this->integer()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
        $this->addForeignKey(
            'fk-user_performance_score-user_id',
            '{{%user_performance_score}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-user_performance_score-user_id', '{{%user_performance_score}}');
        $this->dropTable('{{%user_performance_score}}');
    }

  
}
