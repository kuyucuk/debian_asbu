<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\User;

/**
 * This is the model class for table "user_manager".
 *
 * @property int $id
 * @property int $user_id
 * @property int $manager_id
 */
class UserManager extends ActiveRecord
{
    public static function tableName()
    {
        return 'user_manager';
    }

    public function rules()
    {
        return [
            [['user_id', 'manager_id'], 'required'],
            [['user_id', 'manager_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'manager_id' => 'Manager ID',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getManager()
    {
        return $this->hasOne(User::class, ['id' => 'manager_id']);
    }
}
