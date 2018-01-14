<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "theme".
 *
 * @property int $tid
 * @property string $value
 * @property string $description
 *
 * @property User[] $users
 */
class Theme extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'theme';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'required'],
            [['value', 'description'], 'string', 'max' => 1024],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tid' => 'Tid',
            'value' => 'Value',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['tid' => 'tid']);
    }
}
