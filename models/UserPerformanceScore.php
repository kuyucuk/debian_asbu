<?php

namespace app\models;

use yii\db\ActiveRecord;

class UserPerformanceScore extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_performance_score}}';
    }

    /**
     * Get related User
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'service_year_score', 'education_level_score', 
            'foreign_language_score', 'internal_training_score', 
            'external_training_score', 'committee_participation_score', 
            'education_given_score', 'improvement_activity_score', 
            'internal_meeting_score', 'total_self_score'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'service_year_score' => 'Service Year Score',
            'education_level_score' => 'Education Level Score',
            'foreign_language_score' => 'Foreign Language Score',
            'internal_training_score' => 'Internal Training Score',
            'external_training_score' => 'External Training Score',
            'committee_participation_score' => 'Committee Participation Score',
            'education_given_score' => 'Education Given Score',
            'improvement_activity_score' => 'Improvement Activity Score',
            'internal_meeting_score' => 'Internal Meeting Score',
            'total_self_score' => 'Total Self Score',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
