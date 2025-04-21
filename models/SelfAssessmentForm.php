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
    public $internal_training_files;
    public $external_training_files;
    public $committee_participation_files;
    public $internal_meeting_files;

    public function rules()
    {
        return [
            [['service_years', 'educational_level', 'foreign_language_score'], 'required'],
            [['service_years'], 'integer'],
            [['internal_training_count', 'external_training_count', 'committee_participation_count', 'internal_meeting_count'], 'integer'],
            [['internal_training_files', 'external_training_files', 'committee_participation_files', 'internal_meeting_files'], 
            'file', 
            'skipOnEmpty' => true, 
            'extensions' => 'pdf, jpg, jpeg, png',
            'maxFiles' => 5, //5 adet belge yükleyebilir
            'maxSize' => 5 * 1024 * 1024,], //5 adet belge yükleyebilir 5MB boyutunda en fazla
            [['education_given', 'improvement_activity'], 'boolean'],
            [['education_file', 'improvement_file'], 
            'file', 
            'maxFiles' => 1,
            'maxSize' => 5 * 1024 * 1024,
            'skipOnEmpty' => true, 
            'extensions' => 'pdf, doc, docx, jpg, png'],       
        ];
    }
    public function attributeLabels()
{
    return [
        'service_years' => 'Hizmet yılı',
        'educational_level' => 'Eğitim düzeyi',
        'foreign_language_score' => 'Yabancı dil puanı',
        'internal_training_files' => 'Kurumun düzenlemiş olduğu eğitimler',
        'external_training_files' => 'Kurum dışındaki görev ve hizmetleri',
        'committee_participation_files' => 'Kişinin ana görevleri haricinde komisyon, kurul, ekip gibi görevlendirmeleri',
        'education_given' => 'Kurum içi veya Kurum dışı eğitim verdi mi',
        'education_file' => 'Görevlendirme belgesi',
        'improvement_file' => 'Kurum kültürünü ve işleyişi geliştirici, düzeltici, iyileştirici faaliyetler belgesi',
        'improvement_activity' => 'Kurum kültürünü ve işleyişi geliştirici, düzeltici, iyileştirici faaliyetler',
        'internal_meeting_files' => 'Kurum içindeki panel, eğitim gibi toplantılara katılım',
    ];
}
}
