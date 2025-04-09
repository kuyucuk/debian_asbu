<?php

use yii\db\Migration;

class m250325_114021_user_certificates_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_certificates}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),  // Kullanıcı ID
            'document_name' => $this->string(255)->notNull(),  // Belge adı
            'document_file_path' => $this->string(512)->notNull(),  // Belge dosya yolu
            'document_type' => $this->string(50)->notNull(),  // Belge türü (örneğin: PDF, DOCX)
            'document_category' => $this->string(50)->notNull(),  // Belge kategorisi (örneğin: Sertifika, Eğitim)
            'uploaded_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),  // Yükleme zamanı
        ]);

        // foreign key ekleme: user_id, users tablosundaki id'ye referans verir
        $this->addForeignKey(
            'fk-user_certificates-user_id',
            '{{%user_certificates}}',
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
        $this->dropForeignKey('fk-user_certificates-user_id', '{{%user_certificates}}');

        // Tabloyu kaldır
        $this->dropTable('{{%user_certificates}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250325_114021_user_certificates_table cannot be reverted.\n";

        return false;
    }
    */
}
