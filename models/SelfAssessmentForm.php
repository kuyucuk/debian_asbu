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
    public $education_file;
    public $improvement_activity;
    public $improvement_file;
    public $internal_meeting_count;
    

    public function rules()
    {
        return [
            [['service_years', 'educational_level', 'foreign_language_score'], 'required'],
            [['internal_training_count', 'external_training_count', 'committee_participation_count', 'internal_meeting_count'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf, jpg, jpeg, png'],
            [['education_given', 'improvement_activity'], 'boolean'],
            ['education_file', 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf, doc, docx, jpg, png'],
            ['improvement_file', 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf,doc,docx,jpg,png'],
        ];
    }
    public function attributeLabels()
{
    return [
        'user_id' => 'Kullanıcı',
        'service_years' => 'Hizmet yılı',
        'educational_level' => 'Eğitim düzeyi',
        'foreign_language_score' => 'Yabancı dil puanı',
        'internal_training_count' => 'Kurumun düzenlemiş olduğu eğitimler',
        'external_training_count' => 'Kurum dışındaki görev ve hizmetleri',
        'committee_participation_count' => 'Kişinin ana görevleri haricinde komisyon, kurul, ekip gibi görevlendirmeleri',
        'education_given' => 'Kurum içi veya Kurum dışı eğitim verdi mi',
        'education_file' => 'Görevlendirme belgesi',
        'improvement_file' => 'Kurum kültürünü ve işleyişi geliştirici, düzeltici, iyileştirici faaliyetler belgesi',
        'improvement_activity' => 'Kurum kültürünü ve işleyişi geliştirici, düzeltici, iyileştirici faaliyetler',
        'internal_meeting_count' => 'Kurum içindeki panel, eğitim gibi toplantılara katılım',
    ];
}
}
