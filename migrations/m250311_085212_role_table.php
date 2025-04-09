<?php

use yii\db\Migration;

class m250311_085212_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%role}}', [
            'id' => $this->primaryKey(),  // role_id primary key
            'role_name' => $this->string(255)->notNull(),  // role_name boş olamaz
            'description' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('role');
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
