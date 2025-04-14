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
        [['user_id', 'foreign_language_score', 'internal_training_count', 'external_training_count', 'committee_participation_count', 'internal_meeting_count'], 'integer'],
        [['education_given', 'improvement_activity'], 'boolean'],
        [['service_years'], 'string', 'max' => 20],
        [['educational_level'], 'string', 'max' => 50],
    ];
}
public function attributeLabels()
{
    return [
        'user_id' => 'Kullanıcı',
        'service_years' => 'Hizmet Yılı',
        'educational_level' => 'Eğitim Düzeyi',
        'foreign_language_score' => 'Yabancı Dil Puanı',
        'internal_training_count' => 'İç Eğitim Sayısı',
        'external_training_count' => 'Dış Eğitim Sayısı',
        'committee_participation_count' => 'Komite Katılımı',
        'education_given' => 'Eğitim Verdi mi?',
        'improvement_activity' => 'İyileştirme Faaliyeti Yaptı mı?',
        'internal_meeting_count' => 'İç Toplantı Sayısı',
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

