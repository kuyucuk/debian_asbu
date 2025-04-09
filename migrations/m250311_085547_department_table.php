<?php

use yii\db\Migration;

class m250311_085547_department_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%department}}', [
            'id' => $this->primaryKey(),  
            'department_name' => $this->string()->notNull() ->unique(), 
            'department_type' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('department');
    }
/*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250312_060016_user_manager_table cannot be reverted.\n";

        return false;
    }
    */
}
