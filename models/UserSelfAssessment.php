<?php

namespace app\models;

use yii\db\ActiveRecord;

class UserSelfAssessment extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_self_assessment}}';
    }
    public function rules()
{
    return [
        [['user_id', 'service_years', 'educational_level', 'foreign_language_score'], 'required'],
        [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        [['user_id', 'service_years', 'internal_training_count', 'external_training_count', 'committee_participation_count', 'internal_meeting_count'], 'integer'],
        [['foreign_language_score'], 'string', 'max' => 50],
        [['education_given', 'improvement_activity'], 'boolean'],
        [['educational_level'], 'string', 'max' => 50],
        [['created_at', 'updated_at'], 'safe'],
        [['internal_training_files', 'external_training_files', 'committee_participation_files', 'internal_meeting_files'], 
            'file', 
            'skipOnEmpty' => true, 
            'extensions' => 'pdf, jpg, jpeg, png',
            'maxFiles' => 5, //5 adet belge yükleyebilir
            'maxSize' => 5* 1024 * 1024],
        [['education_file', 'improvement_file'], 
        'file', 
        'maxFiles' => 1,
        'maxSize' => 5* 1024 * 1024,
        'skipOnEmpty' => true, 
        'extensions' => 'pdf, doc, docx, jpg, png'],
    ];
}
public function attributeLabels()
{
    return [
        'user_id' => 'Kullanıcı ID',
        'service_years' => 'Hizmet Yılı',
        'educational_level' => 'Eğitim Düzeyi',
        'foreign_language_score' => 'Yabancı Dil Puanı',
        'internal_training_count' => 'İç Eğitim Sayısı',
        'external_training_count' => 'Dış Eğitim Sayısı',
        'committee_participation_count' => 'Komisyon Katılım Sayısı',
        'education_given' => 'Eğitim Verdi mi?',
        'improvement_activity' => 'İyileştirici Faaliyet Yaptı mı?',
        'internal_meeting_count' => 'İç Toplantı Sayısı',
        'created_at' => 'Oluşturulma Tarihi',
        'updated_at' => 'Güncellenme Tarihi',
    ];
}


    /**
     * Get related User
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
