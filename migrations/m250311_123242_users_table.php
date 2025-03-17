<?php

use yii\db\Migration;

class m250311_123242_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */

    public function safeUp()
    {
        $this->createTable('{{%users}}', [
        
            'id' => $this->primaryKey(),
            'ad' => $this->string(255)->notNull(),
            'soyad' => $this->string(255)->notNull(),
            'kullanici_adi' => $this->string(255)->notNull()->unique(),
            'email' => $this->string(255)->notNull()->unique(),
            'password_hash' => $this->string(255)->notNull(),
            'unvan' => $this->string()->null(),
            'telefon' => $this->string(15)->null(),
            
        ]);
        $this->createIndex(
            'idx-users-kullanici_adi',
            'users',
            'kullanici_adi',
            true
        );
        $this->createIndex(
            'idx-users-email',
            'users',
            'email',
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('users');
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
