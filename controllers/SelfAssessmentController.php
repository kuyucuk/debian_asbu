<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\SelfAssessmentForm;
use app\models\UserSelfAssessment;
use app\models\UserPerformanceScore;
use app\models\UserCertificates;
use yii\web\UploadedFile;

class SelfAssessmentController extends Controller
{
    public function actionIndex()
    {
        $model = new SelfAssessmentForm();
        Yii::$app->view->title = 'Son Kullanıcı Değerlendirme Sayfası'; // Başlık atama
        //geçici olarak PBS den alınacak veriler burada tanımlanır.
        $pbsData = [
            'A1' => 'YDS 70 ve üzeri',
            'B2' => 'YDS 50-69 arası',
            'C1' => 'YDS 49 ve altı',
            'D1' => 'Yabancı dil belgesi yok'
        ];
        $userId = 1; // Sabit ID
        
        // Eğer form gönderildiyse, verileri kaydet.
        if ($model->load(Yii::$app->request->post()) ) {
            // Dosya yükleme işlemleri burada yapılabilir

            // Başarı mesajı veya yönlendirme
            // Yii::$app->session->setFlash('success', 'Değerlendirmenizi başarıyla gönderdiniz.');
            // return $this->refresh(); // Sayfayı yenileyin

            //dosya yükleme işlemi 

            $model->internal_training_count = UploadedFile::getInstance($model, 'internal_training_count');
            $model->external_training_count = UploadedFile::getInstance($model, 'external_training_count');
            $model->committee_participation_count = UploadedFile::getInstance($model, 'committee_participation_count');
            $model->internal_meeting_count = UploadedFile::getInstance($model, 'internal_meeting_count');
            
            if ($model->validate()) {
                // Veriyi user_self_assessment tablosuna kaydet
                $userSelfAssessment = new UserSelfAssessment();
                $userSelfAssessment->user_id = $userId; // Oturum açmış kullanıcı ID'si
                $userSelfAssessment->service_years = $model->service_years;
                $userSelfAssessment->educational_level = $model->educational_level;
                $userSelfAssessment->foreign_language_score = $model->foreign_language_score;
                $userSelfAssessment->education_given = $model->education_given;
                $userSelfAssessment->improvement_activity = $model->improvement_activity;

                if ($userSelfAssessment->save()) {
                    Yii::$app->session->setFlash('success', 'Değerlendirmeniz kaydedildi.');
                } else {
                    Yii::$app->session->setFlash('error', 'Değerlendirme kaydedilirken bir hata oluştu.');
                }

                // Sertifika dosyalarını kaydet
                $uploadPath = Yii::getAlias('@webroot/uploads/');
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true); // uploads klasörü yoksa oluştur
                }


                // Dosyaları kaydet ve ilgili tablolara ekle
                $uploadedFiles = [
                    'internal_training_count' => $model->internal_training_count,
                    'external_training_count' => $model->external_training_count,
                    'committee_participation_count' => $model->committee_participation_count,
                    'internal_meeting_count' => $model->internal_meeting_count
                ];


                foreach ($uploadedFiles as $fileAttribute => $file) {
                    if ($file) {
                        $filePath = $uploadPath . $file->baseName . '.' . $file->extension;
                        if ($file->saveAs($filePath)) {
                            // UserCertificates tablosuna kaydedelim
                            $certificate = new UserCertificates();
                            $certificate->user_id = $userId;
                            $certificate->document_file_path  = '/uploads/' . $file->baseName . '.' . $file->extension;
                            //yukarıdaki satırda hata olursa aşağıdakini dene
                            //$certificate->document_file_path = $filePath; // Doğru dosya yolu
                            $certificate->document_name  = $file->name;
                            $certificate->document_type = $file->extension;
                            $certificate->document_category = 'Eğitim Belgesi';
                            $certificate->uploaded_at = date('Y-m-d H:i:s'); // Yüklenme zamanı
                            $certificate->save();
                        }
                    }
                }
                Yii::$app->session->setFlash('success', 'Değerlendirmeniz ve dosyalarınız başarıyla kaydedildi.');
                return $this->refresh(); // Sayfayı yenileyin
            }

        }

        return $this->render('index', [
            'model' => $model,
            'pbsData' => $pbsData, // PBS verisini view'e aktarma yapar
        ]);
        
    }
}

