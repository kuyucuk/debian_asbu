
<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "user_certificates".
 *
 * @property int $id
 * @property int $user_id
 * @property string $document_name
 * @property string $document_file_path
 * @property string $document_type
 * @property string $document_category
 * @property string $uploaded_at
 *
 * @property Users $user
 */
class UserCertificates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_certificates}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'document_name', 'document_file_path', 'document_type', 'document_category'], 'required'],
            [['user_id'], 'integer'],
            [['document_name', 'document_file_path', 'document_type', 'document_category'], 'string', 'max' => 255],
            [['uploaded_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['document_type'], 'in', 'range' => ['PDF', 'DOCX', 'JPG', 'PNG']],

        ];
    }
    /************** */
//benzersiz dosya adı yükleme işlemi
/******************** */
    public function beforeSave($insert)
{
    if (parent::beforeSave($insert)) {
        if ($this->isNewRecord) {
            // Dosya yolu tam olarak belirtmek
            $uploadPath = Yii::getAlias('@webroot/uploads/certificates/'); // Tam yolu belirtilir
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true); // Eğer klasör yoksa oluştur
                Yii::$app->session->setFlash('success', 'Klasör oluşturuldu.');
            }
            // Benzersiz dosya adı oluşturmak için timestamp veya UUID kullan
            $extension = pathinfo($this->document_file_path, PATHINFO_EXTENSION); // Dosya uzantısını al
            $uniqueName = Yii::$app->security->generateRandomString(10) . '.' . $extension; // Benzersiz isim oluştur
            $this->document_file_path = 'uploads/certificates/' . $uniqueName; // Yeni dosya yolu oluştur
             // Yüklenen dosyayı hedef dizine taşınması
             if ($this->document_file_path && $this->save()) {
                // Dosya kaydetme işlemi
                $file = $this->document_file_path; // Hedef dosya yolu
                $uploadedFile = Yii::$app->basePath . '/web/' . $file; // Tam dosya yolu
                if (!move_uploaded_file($this->document_file_path, $uploadedFile)) {
                    // Hata durumu
                    Yii::error("Dosya kaydedilemedi: " . $file);
                }
            }
        }
        return true;
    }
    return false;
}


    /**
     * {@inheritdoc}
     */
    /*public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Kullanıcı ID',
            'document_name' => 'Belge Adı',
            'document_file_path' => 'Belge Dosya Yolu',
            'document_type' => 'Belge Türü',
            'document_category' => 'Belge Kategorisi',
            'uploaded_at' => 'Yüklenme Zamanı',
        ];
    }*/

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
/********************** */
//belge listeleme ve filtreleme için yazılan model 
/************************** */
class UserCertificatesSearch extends UserCertificates
{
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['document_name', 'document_type', 'document_category', 'uploaded_at'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = UserCertificates::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // Filtreler ekle
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['user_id' => $this->user_id])
            ->andFilterWhere(['like', 'document_name', $this->document_name])
            ->andFilterWhere(['like', 'document_type', $this->document_type])
            ->andFilterWhere(['like', 'document_category', $this->document_category])
            ->andFilterWhere(['like', 'uploaded_at', $this->uploaded_at]);

        return $dataProvider;
    }
}
