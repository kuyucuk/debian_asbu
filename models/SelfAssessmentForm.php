<?php

namespace app\models;

use yii\base\Model;

class SelfAssessmentForm extends Model
{
    public $service_years;
    public $educational_level;
    public $foreign_language_score;
    public $internal_training_count;
    public $external_training_count;
    public $committee_participation_count;
    public $education_given;
    public $improvement_activity;
    public $internal_meeting_count;

    public function rules()
    {
        return [
            [['service_years', 'educational_level', 'foreign_language_score'], 'required'],
            [['internal_training_count', 'external_training_count', 'committee_participation_count', 'internal_meeting_count'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf, jpg, jpeg, png'],
            [['education_given', 'improvement_activity'], 'boolean'],
        ];
    }
}
