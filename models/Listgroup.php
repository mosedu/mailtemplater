<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%listgroup}}".
 *
 * @property integer $lg_id
 * @property string $lg_createtime
 * @property string $lg_name
 */
class Listgroup extends \yii\db\ActiveRecord
{

    /**
     * @return array
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['lg_createtime'],
                ],
                'value' => ( strtolower($this->db->driverName) != 'sqlite' ) ? new Expression('NOW()') : date('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%listgroup}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lg_createtime'], 'safe'],
            [['lg_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lg_id' => 'ID',
            'lg_createtime' => 'Создана',
            'lg_name' => 'Наименование',
        ];
    }
}
