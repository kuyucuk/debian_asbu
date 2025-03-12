<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class User extends ActiveRecord
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

    // Şifreyi hashleme metodu
    public function setPassword($password)
    {
        if (!empty($this->email)) {
            $salt = md5($this->email); 
            $this->password_hash = Yii::$app->security->generatePasswordHash($password . $salt);
        }
    }

    // Şifre doğrulama metodu
    public function validatePassword($password)
    {
        if (!empty($this->email)) {
            $salt = md5($this->email);
            return Yii::$app->security->validatePassword($password . $salt, $this->password_hash);
        }
        return false;
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
}
