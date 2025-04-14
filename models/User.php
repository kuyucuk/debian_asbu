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
            [['ad', 'soyad', 'email'], 'required'], 
            ['kullanici_adi', 'unique'],
            [['ad', 'soyad', 'kullanici_adi'], 'string', 'max' => 255],
            ['email', 'email'], 
            ['email', 'unique'],
        ];
    }
    /*public function attributeLabels()
{
    return [
        'id' => 'ID',
        'ad' => 'Adı',
        'soyad' => 'Soyadı',
        'email' => 'E-Posta',
        'kullanici_adi' => 'Kullanıcı Adı',
        //'unvan' => 'Ünvan',
        //'telefon' => 'Telefon',
        //'password_hash' => 'Şifre',
    ];
}*/


    // Şifreyi hashleme metodu
   /* public function setPassword($password)
    {
        //email bazlı hashleme yapmak istersek bu kodu kullanıyoruz.
        if (!empty($this->email)) {
            $salt = md5($this->email); 
            $this->password_hash = Yii::$app->security->generatePasswordHash($password . $salt);
        }


        //email ile değil de salt şifreleme kodu
        /*if (!empty($password)) { 
            $this->password_hash = Yii::$app->security->generatePasswordHash($password);
        }*
    }*/

    // Şifre doğrulama metodu
   /* public function validatePassword($password)
    {
        //salt şifrelemenin doğrulama kodu
        //return Yii::$app->security->validatePassword($password, $this->password_hash);

        if (!empty($this->email)) {
            $salt = md5($this->email);
            return Yii::$app->security->validatePassword($password . $salt, $this->password_hash);
        }
        return false;
    }*/

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
        if ($this->isNewRecord && empty($this->kullanici_adi) && !empty($this->ad) && !empty($this->soyad)) { //ad ve soyadın boş gelmemesi gerektiğini söylüyor
            $this->kullanici_adi = $this->generateUsername($this->ad, $this->soyad);
        }

        return parent::beforeSave($insert);
    }
    public function getUserDepartments()
{
    return $this->hasMany(UserDepartment::class, ['user_id' => 'id']);
}
}
