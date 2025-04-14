
<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\DetailView;
use yii\web\UploadedFile;

/* @var $this yii\web\View */
/* @var $model app\models\SelfAssessmentForm */

//$this->title = 'Son Kullanıcı Değerlendirme Sayfası';

?>
<div class="self-assessment">

    
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'self-assessment-form']); ?>

    <!-- Soru 1: Radio Button Seçimi -->
    <!-- Asbü deki hizmet yılı -->
    <div class="form-group">
        <?= $form->field($model, 'service_years')->radioList([
            1 => '1-5 yıl arası',
            2 => '6-10 yıl arası',
            3 => '11-15 yıl arası',
            4 => '16-20 yıl arası',
            5 => '21 yıl ve üzeri',
        ]) ?>
    </div>

    <!-- Soru 2: Radio Button Seçimi -->
    <!-- eğitim düzeyi(son mezuniyet)-->
    <div class="form-group">
        <?= $form->field($model, 'educational_level')->radioList([
            1 => 'ilköğretim/Ortaöğretim',
            2 => 'Ön Lisans',
            3 => 'Lisans',
            4 => 'Yüksek Lisans',
            5 => 'Doktora',
        ]) ?>
    </div>

    <!-- Soru 3: PBS den Veri -->
    <!-- Geçerliliği devam eden yabancı dil puanına sahiplik -->
    <div class="form-group">
         <?= $form->field($model, 'foreign_language_score')->dropDownList($pbsData, ['prompt' => 'Bir seçenek seçin']) ?>
    </div>

    <!-- Soru 4, 5, 6: Dosya Yükleme -->
    <!-- Kurumun düzenlemiş olduğu eğitimlerin gerçekleştirilmesinde rol oynar.
    (Sunucu olarak yer alma, teknik destek gibi görevlendirme olurları eklenecek)
    *Maksimum 5 tane - her görevlendirme 1 puan -->
    <div class="form-group">
        <?= $form->field($model, 'internal_training_count')->fileInput(['accept' => 'application/pdf, image/*']) ?>
    </div>
    <!-- Kendi kurumu dışında görev ve hizmet tanımına katkı sağlayacak eğitimlere katılmıştır. 
    (Bu bölümde aynı sene katılınan eğitimlerin sertifikaları yüklenecek)
    *Her bir sertifika 1 puan, maksimum 5 sertifika yüklenecek. -->
    <div class="form-group">
        <?= $form->field($model, 'external_training_count')->fileInput(['accept' => 'application/pdf, image/*']) ?>
    </div>
    <!-- Kişinin ana görevleri haricinde komisyon, kurul, ekip gibi görevlendirmelere sahiptir.
    (KVKK Komisyonu, kalite kurulu, risk komisyonu gibi görevlendirme olurları eklenecek)
    *Maksimum 5 tane - her görevlendirme 1 puan -->
    <div class="form-group">
        <?= $form->field($model, 'committee_participation_count')->fileInput(['accept' => 'application/pdf, image/*']) ?>
    </div>

    <!-- Soru 7: Evet / Hayır -->
    <!-- Kurum içi veya kurum dışı eğitim verip vermediği -->
    <div class="form-group">
        <?= $form->field($model, 'education_given')->radioList([
            1 => 'Evet',
            0 => 'Hayır',
        ]) ?>
    </div>
   
<!-- Eğitim verdiyse belgenin yükleneceği alan (başta gizli olacak) -->
<div id="education-file-upload" style="display:none;">
    <?= $form->field($model, 'education_file')->fileInput() ?>
</div>

    <!-- Soru 8: Evet / Hayır -->
    <!-- Kurum kültürünü ve işleyişi geliştirici düzeltici iyileştirici faaliyetlerde bulunmuştur. -->
    <div class="form-group">
        <?= $form->field($model, 'improvement_activity')->radioList([
            1 => 'Evet',
            0 => 'Hayır',
        ]) ?>
    </div>

    <!-- Soru 9: Dosya Yükleme -->
    <!-- Kurum içindeki panel, eğitim gibi toplantılara katılım sağlamıştır.
    *Maksimum 5 tane - her katılım 1 puan -->
    <div class="form-group">
        <?= $form->field($model, 'internal_meeting_count')->fileInput(['accept' => 'application/pdf, image/*']) ?>
    </div>

    <!-- Gönder Butonu -->
    <div class="form-group">
        <?= Html::submitButton('Değerlendirmenizi Gönder', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
$script = <<< JS
    // Sayfa yüklendiğinde belge alanını gizle
    $('#education-file-upload').hide();

    // Radio buton değiştiğinde kontrol et
    $('input[name="SelfAssessmentForm[education_given]"]').on('change', function() {
        if ($(this).val() == '1') {
            $('#education-file-upload').slideDown();  // Evet seçildi
        } else {
            $('#education-file-upload').slideUp();    // Hayır seçildi
        }
    });
JS;

$this->registerJs($script);
