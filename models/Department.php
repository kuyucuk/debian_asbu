<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "department".
 *
 * @property int $id
 * @property string $department_name
 */
class Department extends ActiveRecord
{
    public static function tableName()
    {
        return 'department';
    }

    public function rules()
    {
        return [
            [['department_name'], 'required'],
            [['department_name'], 'string', 'max' => 255],
            [['department_name'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'department_name' => 'Department Name',
        ];
    }
//user-department da id kullanılıyor
    public function getUserDepartments()
    {
        return $this->hasMany(UserDepartment::class, ['department_id' => 'id']);
    }
}
