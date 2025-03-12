<?php

use yii\db\Migration;

class m250312_055534_user_department_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_department}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'department_id' => $this->integer()->notNull(),
            'role_id' => $this->integer()->notNull(), // Kullanıcının bu departmandaki rolü
            
        ]);

        // Foreign Key (user_id)
        //user tablosundaki id alınarak FK yapılmıştır
        $this->addForeignKey(
            'fk-user_department-user_id',
            'user_department',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        // Foreign Key (department_id)
        $this->addForeignKey(
            'fk-user_department-department_id',
            'user_department',
            'department_id',
            'department',
            'id',
            'CASCADE'
        );

        // Foreign Key (role_id) => Kullanıcının departmandaki rolü
        $this->addForeignKey(
            'fk-user_department-role_id',
            'user_department',
            'role_id',
            'role',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Önce Foreign Key'leri kaldır
        $this->dropForeignKey('fk-user_department-user_id', 'user_department');
        $this->dropForeignKey('fk-user_department-department_id', 'user_department');
        $this->dropForeignKey('fk-user_department-role_id', 'user_department');

 
        $this->dropTable('user_department');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250312_055534_user_department_table cannot be reverted.\n";

        return false;
    }
    */
}
