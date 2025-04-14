<?php

use yii\db\Migration;

class m250325_074301_user_self_assessment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_self_assessment}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'service_years' => $this->string(20)->notNull(),
            'educational_level' => $this->string(50)->notNull(),
            'foreign_language_score' => $this->integer()->notNull(),
            'internal_training_count' => $this->integer()->defaultValue(0),
            'external_training_count' => $this->integer()->defaultValue(0),
            'committee_participation_count' => $this->integer()->defaultValue(0),
            'education_given' => $this->boolean()->defaultValue(false)->notNull(),
            'improvement_activity' => $this->boolean()->defaultValue(false) ->notNull(),
            'internal_meeting_count' => $this->integer()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
        $this->addForeignKey(
            'fk-user_self_assessment-user_id',
            '{{%user_self_assessment}}',
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
        $this->dropForeignKey('fk-user_self_assessment-user_id', '{{%user_self_assessment}}');

        // Tabloyu kaldır
        $this->dropTable('{{%user_self_assessment}}');
    }

    
}
