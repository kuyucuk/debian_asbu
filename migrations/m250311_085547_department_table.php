<?php

use yii\db\Migration;

class m250311_085547_department_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('department', [
            'department_id' => $this->primaryKey(),  
            'departmant_name' => $this->string()->notNull(),  
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
        echo "m250311_085547_department_table cannot be reverted.\n";

        return false;
    }
    */
}
