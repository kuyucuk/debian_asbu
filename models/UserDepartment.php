<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\User;
use app\models\Department;
use app\models\Role;

/**
 * This is the model class for table "user_department".
 *
 * @property int $id
 * @property int $user_id
 * @property int $department_id
 * @property int $role_id
 */
class UserDepartment extends ActiveRecord
{
    public static function tableName()
    {
        return 'user_department';
    }

    public function rules()
    {
        return [
            [['user_id', 'department_id', 'role_id'], 'required'],
            [['user_id', 'department_id', 'role_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'department_id' => 'Department ID',
            'role_id' => 'Role ID',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getDepartment()
    {
        return $this->hasOne(Department::class, ['id' => 'department_id']);
    }

    public function getRole()
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }
}
