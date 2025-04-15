<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Performans Ölçüm Formu';
?>

<div class="site-performans">
    <h2>ANKARA SOSYAL BİLİMLER ÜNİVERSİTESİ<br>İDARİ PERSONEL PERFORMANS ÖLÇÜM KRİTERLERİ</h2>
    <p><strong>1. İŞ BECERİSİ (Her soru 1 puan, Ağırlık %30)</strong></p>

    <?php $form = ActiveForm::begin([
        'options' => ['onsubmit' => 'return handleFormSubmit(event)']
    ]); ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Soru No</th>
                <th>İfade</th>
                <th>Kesinlikle Katılıyorum</th>
                <th>Katılıyorum</th>
                <th>Ne Katılıyorum Ne Katılmıyorum</th>
                <th>Katılmıyorum</th>
                <th>Kesinlikle Katılmıyorum</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sorular = [
                'Görevin gerektirdiği yeterli bilgi ve deneyime sahip ve görevleri yerine getirmede gayretlidir.',
                'Kendisine verilen görevi sahiplenir, bütüne etkilerini görerek, görevlerini tam ve doğru yapar.',
                'Görevin iş gereklerine uygun bir biçimde tamamlanması için gerekli kontrolleri yapar.',
                'Gerektiğinde farklı görevleri bir arada yürütebilir.',
                'Verilen işleri süresi içinde, iş akışında gecikmelere neden olmadan tamamlar.',
                'Sorumluluğundaki işlerin tamamlanması için gerektiğinde ek çalışmalara katılma konusunda isteklidir.',
                'Görüş ve düşüncelerini, yazılı ve sözlü, sözsüz iletişim becerilerini kullanarak açık ve net bir şekilde ifade eder.',
                'İş ilişkisinde olduğu her düzeyde kişiyle yapıcı ilişkiler kurar.',
                'Etkili dinleme becerisine sahiptir.',
                'İş ilişkilerinde yapıcı olarak geri bildirim alır ve verir.',
                'İş ilişkisinde bulunduğu kişilerle uyumlu ve verimli bir şekilde, ortak hedefler için işbirliği içinde çalışır.',
                'Bilgi, beceri ve deneyimlerini çalışma arkadaşlarıyla paylaşır.',
                'Kurum bünyesinde oluşturulmuş kurallara uyum konusunda gerekli hassasiyeti gösterir.',
                'Kamu kaynaklarının etkin ve verimli kullanımı ilkesiyle araç, gereç ve malzemelere özen gösterir.',
                'Sorun ve anlaşmazlıkların konu ve etki alanlarının her zaman farkındadır.',
                'Sorun ve anlaşmazlıklara ilişkin neden ve sonuçları değerlendirir, alternatifleri belirler.',
                'Sorun ve anlaşmazlıkları hızlı, doğru ve kalıcı bir biçimde çözer.',
                'İşini standartlara ve mevzuata uygun olarak yapabilme becerisi yüksektir.',
                'Görevlerin ifası için sorun, kişi, araç, zaman ve diğer kaynakları belirler ve organize eder.',
                'Pozitif düşünür, kendini ve çalışanlarını motive edici olumlu liderlik etkileri ortaya koyar.',
                'Faaliyetleri denetleyerek ve düzeltici önlemleri alarak kontrol işlevini yerine getirir.',
                'İşlerin dizaynında (iş yükü, iş bölümü, iş genişletme, iş zenginleştirme, yetkilendirme, sorumluluk vermede) adil ve dikkatli davranır.',
                'Kendisini ilgilendiren konularda karar alma ve inisiyatif kullanabilme becerisi yüksektir.',
                'Çalışmalarını programlayarak; önem ve aciliyet sırasında işi bitirebilme becerisi yüksektir.',
                'Astlarına örnek olur ve kendilerini geliştirmelerinde rehberlik ederek imkânlar sunar.',
                'Değişiklik/yeniliklere açıktır, astlarının da gelişmelere hızla uyum göstermelerini sağlar.',
                'Bilgi ve becerileri kazanmalarında araştırmalar yapar ve personeli için eğitim ve geliştirme fırsatlarını değerlendirir.',
                'Mevzuat ve teknoloji değişikliklerini sürekli takip eder.',
                'Kurumun ve görevin mahiyetine uygun fiziksel ve davranışsal olgulara önem verir.',
                'Katıldığı eğitimlerde öğrendiklerini işine yansıtır.'
            ];

            foreach ($sorular as $i => $soru) {
                $numara = $i + 1;
                echo "<tr class='soru-row' data-row='$i'>";
                echo "<td>1.$numara</td>";
                echo "<td class='text-left'>$soru</td>";
                for ($j = 5; $j >= 1; $j--) {
                    echo "<td><input type='radio' name='cevap[$i]' value='$j' class='cevap' data-row='$i' onchange='updateRowColor($i)'></td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="form-group text-right">
        <button type="button" class="btn btn-secondary" style="background-color:red; color: white;" onclick="window.history.back()">Geri</button>
        <?= Html::submitButton('Kaydet', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<style>
body {
    padding-top: 50px; /* Navbar yüksekliği kadar üstten boşluk bırakır */
}
.table-responsive {
    overflow-x: auto;
}
th, td {
    vertical-align: middle;
    text-align: center;
}
th:first-child, td:first-child, td:nth-child(2) {
    text-align: left;
}
.form-group.text-right {
    text-align: right;
}
.soru-row.answered {
    background-color: #d4edda; /* Yeşil renk */
    color: black;
}
</style>

<script>
function updateRowColor(rowIndex) {
    const row = document.querySelector(`.soru-row[data-row='${rowIndex}']`);
    const inputs = row.querySelectorAll('.cevap');
    const isAnswered = Array.from(inputs).some(input => input.checked);

    if (isAnswered) {
        row.classList.add('answered');
    } else {
        row.classList.remove('answered');
    }
}

function handleFormSubmit(event) {
    event.preventDefault(); // Formun varsayılan gönderimini engelle

    let allAnswered = true;
    const inputs = document.querySelectorAll('.cevap');

    // Kontrol
    const groupedInputs = {};
    inputs.forEach(input => {
        const row = input.dataset.row;
        if (!groupedInputs[row]) {
            groupedInputs[row] = [];
        }
        groupedInputs[row].push(input);
    });

    for (const group in groupedInputs) {
        const isAnswered = groupedInputs[group].some(input => input.checked);
        if (!isAnswered) {
            allAnswered = false;
        }
    }

    if (!allAnswered) {
        alert('Lütfen tüm soruları cevaplayınız.');
        return false;
    }

    alert('Ölçüm kaydedilmiştir');
    window.history.back(); // Bir önceki sayfaya dön
}
</script>
