<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%listelement}}".
 *
 * @property integer $le_id
 * @property string $le_createtime
 * @property string $le_email
 * @property string $le_fam
 * @property string $le_name
 * @property string $le_otch
 * @property string $le_org
 * @property array $_allgroups
 */
class Listelement extends \yii\db\ActiveRecord
{

    public $_allgroups;

    /**
     * @return array
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['le_createtime'],
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
        return '{{%listelement}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['le_createtime'], 'safe'],
            [['_allgroups'], 'in', 'range' => []],
            [['le_email', 'le_fam', 'le_name', 'le_otch', 'le_org'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'le_id' => 'ID',
            'le_createtime' => 'Создан',
            'le_email' => 'Email',
            'le_fam' => 'Фамилия',
            'le_name' => 'Имя',
            'le_otch' => 'Отчество',
            'le_org' => 'Организация',
            '_allgroups' => 'Группы',
        ];
    }

    public function getGroups() {
//        return $this->hasMany();
    }

}
