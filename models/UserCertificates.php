<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "user_certificates".
 *
 * @property int $id
 * @property int $user_id
 * @property string $document_name
 * @property string $document_file_path
 * @property string $document_type
 * @property string $document_category
 * @property string $uploaded_at
 *
 * @property Users $user
 */
class UserCertificates extends \yii\db\ActiveRecord
{
    public $document_file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_certificates}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'document_name', 'document_file_path', 'document_type', 'document_category'], 'required'],
            [['user_id'], 'integer'],
            [['document_name', 'document_file_path', 'document_type', 'document_category'], 'string', 'max' => 255],
            [['uploaded_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['document_type'], 'in', 'range' => ['PDF', 'DOCX', 'JPG', 'PNG']],
            [['document_file'], 'file', 'extensions' => ['pdf', 'docx', 'jpg', 'png'], 'skipOnEmpty' => true],
        ];
    }
}
