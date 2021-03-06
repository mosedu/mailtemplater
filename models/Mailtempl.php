<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%mailtempl}}".
 *
 * @property integer $mt_id
 * @property string $mt_createtime
 * @property string $mt_name
 * @property string $mt_text
 */
class Mailtempl extends \yii\db\ActiveRecord
{

    /**
     * @return array
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['mt_createtime'],
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
        return '{{%mailtempl}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mt_createtime'], 'safe'],
            [['mt_text'], 'string'],
            [['mt_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mt_id' => 'ID',
            'mt_createtime' => 'Создан',
            'mt_name' => 'Название',
            'mt_text' => 'Текст',
        ];
    }
}
