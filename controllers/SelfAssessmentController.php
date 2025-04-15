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
            $model->education_file = UploadedFile::getInstance($model, 'education_file'); //eğitim verdiyse belge eklenmesi
            
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
                    Yii::$app->session->setFlash('success', 'Değerlendirmeniz ve dosyalarınız başarıyla kaydedildi.');
                    return $this->refresh();
                } else {
                    Yii::$app->session->setFlash('error', 'Değerlendirme kaydedilirken bir hata oluştu: ' . implode(', ', $userSelfAssessment->getFirstErrors()));
                    return $this->refresh();
                }
                
                // Sertifika dosyalarını kaydet
                $uploadPath = Yii::getAlias('@webroot/uploads/certificates/');
                if (!is_dir($uploadPath)) {
                    if (!mkdir($uploadPath, 0777, true)) {
                        Yii::$app->session->setFlash('error', 'Yükleme dizini oluşturulamadı.');
                        return $this->refresh();
                    }
                }


                // Dosyaları kaydet ve ilgili tablolara ekle
                // Dosya-kategori eşleşmesi
                $uploadedFiles = [
                    'internal_training_count' => $model->internal_training_count,
                    'external_training_count' => $model->external_training_count,
                    'committee_participation_count' => $model->committee_participation_count,
                    'internal_meeting_count' => $model->internal_meeting_count,
                    'education_file' => $model->education_file
                ];
//kategoriler burada tanımlandı değiştirilebilir. 
                $documentCategories = [
                    'internal_training_count' => 'İç Eğitim Belgesi',
                    'external_training_count' => 'Dış Eğitim Belgesi',
                    'committee_participation_count' => 'Komisyon Katılım Belgesi',
                    'internal_meeting_count' => 'İç Toplantı Katılım Belgesi',
                    'education_file' => 'Görevlendirme'
                ];
                // Kontrol etmek için
//var_dump($uploadedFiles);
//exit;

                foreach ($uploadedFiles as $fileAttribute => $file) {
                    if ($file) {
                        $uniqueName = Yii::$app->security->generateRandomString(10) . '.' . $file->extension;
                        $uploadSubPath = 'uploads/certificates/' . $uniqueName;
                        $fullPath = Yii::getAlias('@webroot/' . $uploadSubPath);
                        //$fullPath = $uploadPath . $file->baseName . '.' . $file->extension;
                        if ($file->saveAs($fullPath)) {
                            // UserCertificates tablosuna kaydedelim
                            $certificate = new UserCertificates();
                            $certificate->user_id = $userId;
                            $certificate->document_name  = $file->name;
                            $certificate->document_file_path  = $uploadSubPath;
                            //yukarıdaki $uploadSubPath; yaılanın yerine önceden yazdığım kod :'/uploads/' . $file->baseName . '.' . $file->extension; //yüklenen dosyanın yolu
                            //yukarıdaki satırda hata olursa aşağıdakini dene
                            //$certificate->document_file_path = $fullPath; // Doğru dosya yolu
                            $certificate->document_type = strtoupper($file->extension); //dosya uzantısı
                            $certificate->document_category = $documentCategories[$fileAttribute] ?? 'Bilinmeyen'; //dosya kategorisi
                            $certificate->uploaded_at = date('Y-m-d H:i:s'); // Yüklenme zamanı
                            //$certificate->save();
                            if ($certificate->save()) {
                                // Sertifika başarıyla kaydedildi
                                Yii::$app->session->addFlash('success', 'Belge (' . $file->name . ') başarıyla kaydedildi.');
                            } else {
                                Yii::$app->session->addFlash('error', 'Belge (' . $file->name . ') kaydedilemedi: ' . implode(', ', $certificate->getFirstErrors()));
                            }
                        }
                        else {
                            Yii::$app->session->addFlash('error', 'Dosya yüklenemedi. Lütfen tekrar deneyin.' . $file->name);
                        }
                    }
                }
                Yii::$app->session->setFlash('success', 'Değerlendirmeniz ve dosyalarınız başarıyla kaydedildi.');
                return $this->refresh(); // Sayfayı yenileyin
            }
            else {
                Yii::$app->session->setFlash('error', 'Doğrulama hatası: ' . implode(", ", $model->getFirstErrors()));
                return $this->refresh(); // Sayfayı yenileyin
            }

        }

        return $this->render('index', [
            'model' => $model,
            'pbsData' => $pbsData, // PBS verisini view'e aktarma yapar
        ]);
        
    }

}