<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "role".
 *
 * @property int $id
 * @property string $role_name
 */
class Role extends ActiveRecord
{
    public static function tableName()
    {
        return 'role';
    }

    public function rules()
    {
        return [
            [['role_name'], 'required'],
            [['role_name'], 'string', 'max' => 255],
            [['role_name'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_name' => 'Role Name',
        ];
    }
//user-department tarafında kullanılıyor
    public function getUserDepartments()
    {
        return $this->hasMany(UserDepartment::class, ['role_id' => 'id']);
    }
}