<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'users';  
    }

    public function rules()
    {
        return [
            [['ad', 'soyad', 'email', 'password_hash'], 'required'], 
            ['kullanici_adi', 'unique'],
            [['ad', 'soyad', 'kullanici_adi', 'unvan', 'telefon'], 'string'],
            ['email', 'email'], 
            ['email', 'unique'],
        ];
    }

    // Kullanıcıyı kullanıcı adına göre bulur
    public static function findByUsername($username)
    {
        return static::findOne(['kullanici_adi' => $username]);
    }

    // Şifreyi hashleme metodu (DÜZELTİLDİ)
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    // Şifre doğrulama metodu (DÜZELTİLDİ)
    public function validatePassword($password)
{
    return $password === $this->password_hash; // Test için düz karşılaştırma
}


    // Kullanıcı adı oluşturma fonksiyonu
    public function generateUsername($ad, $soyad)
    {
        $turkishChars = [
            'ç' => 'c', 'Ç' => 'C', 'ğ' => 'g', 'Ğ' => 'G',
            'ı' => 'i', 'İ' => 'I', 'ş' => 's', 'Ş' => 'S',
            'ö' => 'o', 'Ö' => 'O', 'ü' => 'u', 'Ü' => 'U'
        ];

        $ad = strtolower($ad);
        $soyad = strtolower($soyad);

        $ad = strtr($ad, $turkishChars);
        $soyad = strtr($soyad, $turkishChars);
        $kullanici_adi = $ad . '.' . $soyad;
        return $kullanici_adi;
    }

    // Kayıttan önce kullanıcı adını oluşturma
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->kullanici_adi = $this->generateUsername($this->ad, $this->soyad);
        }

        return parent::beforeSave($insert);
    }

    // IdentityInterface Metodları

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null; // Access Token kullanmıyorsanız null dönebilirsiniz.
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null; // Eğer authKey kullanmıyorsanız null dönebilirsiniz.
    }

    public function validateAuthKey($authKey)
    {
        return false; // Eğer authKey kullanmıyorsanız false dönebilirsiniz.
    }
}
