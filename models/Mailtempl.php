<?php

namespace app\models;

use Yii;

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
            'mt_id' => 'Mt ID',
            'mt_createtime' => 'Mt Createtime',
            'mt_name' => 'Mt Name',
            'mt_text' => 'Mt Text',
        ];
    }
}
