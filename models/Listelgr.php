<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%listelgr}}".
 *
 * @property integer $elgr_id
 * @property integer $elgr_le_id
 * @property integer $elgr_lg_id
 */
class Listelgr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%listelgr}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['elgr_le_id', 'elgr_lg_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'elgr_id' => 'Elgr ID',
            'elgr_le_id' => 'Elgr Le ID',
            'elgr_lg_id' => 'Elgr Lg ID',
        ];
    }
}
