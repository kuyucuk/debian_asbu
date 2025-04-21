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
       
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            Yii::error('Form post edildi ve model yüklendi', 'self-assessment-flow');
            // model alanları
            $model->internal_training_files = UploadedFile::getInstances($model, 'internal_training_files'); 
            $model->external_training_files = UploadedFile::getInstances($model, 'external_training_files');
            $model->committee_participation_files = UploadedFile::getInstances($model, 'committee_participation_files');
            $model->internal_meeting_files = UploadedFile::getInstances($model, 'internal_meeting_files');
            $model->education_file = UploadedFile::getInstance($model, 'education_file');
            $model->improvement_file = UploadedFile::getInstance($model, 'improvement_file');
        
            // Dosya klasörü oluşturma
            $uploadDir = Yii::getAlias('@webroot/uploads/certificates/');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }
        
            if ($model->validate()) {
            Yii::error('Model doğrulandı', 'self-assessment-flow');
            //UserSelfAssessment modeline veri gönderme
            $assessment = new UserSelfAssessment();
            $assessment->user_id = $userId;
            $assessment->service_years = $model->service_years;
            $assessment->educational_level = $model->educational_level;
            $assessment->foreign_language_score = $model->foreign_language_score;
            $assessment->education_given = $model->education_given ? 1 : 0;
            $assessment->internal_training_count = count($model->internal_training_files);
            $assessment->external_training_count = count($model->external_training_files);
            $assessment->committee_participation_count = count($model->committee_participation_files);
            $assessment->improvement_activity = $model->improvement_activity ? 1 : 0;
            $assessment->internal_meeting_count = count($model->internal_meeting_files);
            $assessment->created_at = date('Y-m-d H:i:s');
            $assessment->updated_at = date('Y-m-d H:i:s');
            $assessment->save(false);
            
            //kategoriler burada tanımlandı değiştirilebilir. 
            $documentCategories = [
                'internal_training_files' => 'İç Eğitim Belgesi',
                'external_training_files' => 'Dış Eğitim Belgesi',
                'committee_participation_files' => 'Komisyon Katılım Belgesi',
                'internal_meeting_files' => 'İç Toplantı Katılım Belgesi',
                'education_file' => 'Görevlendirme',
                'improvement_file' => 'İyileştirici Faaliyet belgesi'
            ];

            $multiUploadFields = [
                'internal_training_files',
                'external_training_files',
                'committee_participation_files',
                'internal_meeting_files',
            ];
            
            foreach ($multiUploadFields as $field) { //çoklu dosya alacağı için döngüye soktuk
                if (!empty($model->$field)) {
                    foreach ($model->$field as $file) {
                        if ($file instanceof UploadedFile) {
                            $uniqueName = Yii::$app->security->generateRandomString(10) . '.' . $file->extension;
                            $uploadSubPath = 'uploads/certificates/' . $uniqueName;
                            $fullPath = Yii::getAlias('@webroot/' . $uploadSubPath);
                            if ($file->saveAs($fullPath)) {
                                $certificate = new UserCertificates();
                                $certificate->user_id = $userId;
                                $certificate->document_name  = $file->name;
                                $certificate->document_file_path  = $uploadSubPath;
                                $certificate->document_type = strtoupper($file->extension);
                                $certificate->document_category = $documentCategories[$field] ?? 'Bilinmeyen';
                                $certificate->uploaded_at = date('Y-m-d H:i:s');
                                if($certificate->save(false)){
                                Yii::$app->session->addFlash('success', 'çoklu dosyalar kaydedildi.');
                                }
                            }
                        }
                    }
                }
            }

            $simpleFiles = [
                'education_file',
                'improvement_file',
            ];

            foreach ($simpleFiles as $singleFile) {  //tekli dosyaları yükleme alanı
                $file = $model->$singleFile;
                if ($file instanceof UploadedFile) {
                    $uniqueName = Yii::$app->security->generateRandomString(10) . '.' . $file->extension;
                    $uploadSubPath = 'uploads/certificates/' . $uniqueName;
                    $fullPath = Yii::getAlias('@webroot/' . $uploadSubPath);
                    if ($file->saveAs($fullPath)) {
                        $certificate = new UserCertificates();
                        $certificate->user_id = $userId;
                        $certificate->document_name  = $file->name;
                        $certificate->document_file_path  = $uploadSubPath;
                        $certificate->document_type = strtoupper($file->extension);
                        $certificate->document_category = $documentCategories[$singleFile] ?? 'Bilinmeyen';
                        $certificate->uploaded_at = date('Y-m-d H:i:s');
                        if($certificate->save(false)){
                        Yii::$app->session->addFlash('success', 'tekli dosyalar kaydedildi');
                        }
                    }    
                }
            }
            if($assessment->save(false)) {
                Yii::error('Kayıt başarılı olarak save edildi.', 'self-assessment-debug');
            } else {
                Yii::error('SAVE başarısız oldu:', 'self-assessment-debug');
                Yii::error($assessment->getErrors(), 'self-assessment-debug');
            }
        } 
        else {
            Yii::error($model->errors, 'self-assessment-validation-errors');
        }
    }
        
        return $this->render('index', [
            'model' => $model,
            'pbsData' => $pbsData, // PBS verisini view'e aktarma yapar
        ]); 
    }
}