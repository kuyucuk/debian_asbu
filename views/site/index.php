<?php
/** @var yii\web\View $this */


$this->title = 'PPD';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <div class="d-flex align-items-center justify-content-center mb-4">
            <!-- Logo eklendi -->
            <a href="https://www.asbu.edu.tr" class="d-inline-block">
                <img src="/images/new-asbu-logo-tr.jpg" alt="Üniversite Logosu" style="height: 100px; margin-right: 30px;">
            </a>
            <!-- <img src="/images/logo.jpg" alt="Üniversite Logosu" style="height: 120px; margin-right: 40px;"> -->
            <h1 class="display-5" style="font-size: 1.8rem; margin: 0;">İDARİ PERSONEL PERFORMANS ÖLÇÜMÜ</h1>
        </div>

        <p><a class="btn btn-lg btn-success" href="/index.php?r=site%2Flogin">Giriş Yapmak İçin Tıklayın</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4 mb-3">
                <h2>Son Kullanıcı Aşaması</h2>

                <p>Çalışanlar sistemden bilgilerini çekip sertifikalarını yükleyebilir.</p>

                <p><a class="btn btn-outline-secondary" href="/index.php?r=self-assessment">Giriş &raquo;</a></p>
            </div>
            <div class="col-lg-4 mb-3">
                <h2>Üst Yönetici Aşaması</h2>

                <p>Yöneticiler girilen bilgileri onaylayıp iş becerisi bölümünü değerlendirir.</p>

                <p><a class="btn btn-outline-secondary" href="https://www.yiiframework.com/forum/">Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Genel Değerlendirme Aşaması</h2>

                <p>Puanlar toplanıp raporlanır.</p>

                <p><a class="btn btn-outline-secondary" href="https://www.yiiframework.com/extensions/">Eklentiler &raquo;</a></p>
                
            </div>
            <div class="col-lg-4">
                <h2>Ölçek</h2>

                <p>30 soruluk ölçek</p>

                <p><a class="btn btn-outline-secondary" href="/index.php?r=site/olcek">Giriş &raquo;</a></p>
                
            </div>
        </div>

    </div>
</div>
