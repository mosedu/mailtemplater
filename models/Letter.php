<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%letter}}".
 *
 * @property integer $let_id
 * @property string $let_createtime
 * @property string $let_sendtime
 * @property integer $let_mt_id
 * @property string $let_subject
 * @property string $let_text
 * @property integer $let_us_id
 * @property integer $let_send_id
 * @property integer $let_state
 * @property integer $let_send_num
 */
class Letter extends ActiveRecord
{

    /**
     * @return array
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['let_createtime'],
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
        return '{{%letter}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['let_text', 'let_subject', ], 'required'],
            [['let_createtime', 'let_sendtime'], 'safe'],
            [['let_mt_id', 'let_us_id', 'let_send_id', 'let_state', 'let_send_num'], 'integer'],
            [['let_text', 'let_subject', ], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'let_id' => 'Let ID',
            'let_createtime' => 'Создано',
            'let_sendtime' => 'Отправлено',
            'let_mt_id' => 'Шаблон',
            'let_subject' => 'Тема письма',
            'let_text' => 'Текст письма',
            'let_us_id' => 'Отправитель',
            'let_send_id' => 'id рассылки',
            'let_state' => 'Флаг состояния',
            'let_send_num' => 'Процент отправки',
        ];
    }
}
