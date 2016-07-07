<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%listgroup}}".
 *
 * @property integer $lg_id
 * @property string $lg_createtime
 * @property string $lg_name
 */
class Listgroup extends \yii\db\ActiveRecord
{
    public static $_aList = null;

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
            [['lg_name'], 'unique', ],
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

    /**
     * @inheritdoc
     */
    public static function getList($bForce = false) {
        if( $bForce || (self::$_aList === null) ) {
            $_aModel =  self::find()->orderBy('lg_name')->all();
            self::$_aList = ArrayHelper::map($_aModel, 'lg_id', 'lg_name');
        }
        return self::$_aList;
    }

    /**
     * @param string $grName
     * @return integer
     */
    public static function getGroupIdByTitle($grName) {
        $grName = trim($grName);
        $ob = self::find()->where(['lg_name' => $grName, ])->one();
        $id = 0;
        if( $ob === null ) {
            $ob = new Listgroup();
            $ob->lg_name = $grName;
            if( !$ob->save() ) {
                Yii::error('Error save group in getGroupIdByTitle: ' . print_r($ob->getErrors(), true) . "\nattributes: " . print_r($ob->attributes, true));
            }
            else {
                $id = $ob->lg_id;
            }
        }
        else {
            $id = $ob->lg_id;
        }
        return $id;
    }

}
