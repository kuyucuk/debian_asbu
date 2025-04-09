<?php

use yii\db\Migration;

class m250312_060016_user_manager_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        //$this->createTable('user_manager', [
        $this->createTable('{{%user_manager}}', [
            'id' => $this->primaryKey(),
            //'id' => $this->integer()->primaryKey(),
            'user_id' => $this->integer()->notNull(),   // Kullanıcı ID'si
            'user_name' => $this->string(255)->notNull(),
            'manager_id' => $this->integer()->notNull(), // Yönetici ID'si
            'manager_name' => $this->string(255)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Foreign Key: user_id -> users(id)
        $this->addForeignKey(
            'fk-user_manager-user_id',
            'user_manager',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );

        // Foreign Key: manager_id -> users(id) (Yönetici de kullanıcı tablosundan gelecek)
        $this->addForeignKey(
            'fk-user_manager-manager_id',
            'user_manager',
            'manager_id',
            'users',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Foreign Key'leri kaldır
        $this->dropForeignKey('fk-user_manager-user_id', 'user_manager');
        $this->dropForeignKey('fk-user_manager-manager_id', 'user_manager');

        // Tabloyu kaldır
        $this->dropTable('user_manager');
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
    

