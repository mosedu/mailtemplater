<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use app\models\Listgroup;
use app\models\Listelgr;
use yii\helpers\ArrayHelper;

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
    public $_groupslist;

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
            [['le_email', '_groupslist', ], 'required', ], // '_allgroups',
            [['le_email'], 'unique', ],
//            [['le_createtime'], 'safe', ],
//            [['_allgroups'], 'in', 'range' => array_keys(Listgroup::getList()), 'allowArray' => true, ],
            [['_groupslist'], 'safe', ],
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
            '_groupslist' => 'Группы',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElGroups() {
        return $this->hasMany(
            Listelgr::className(),
            ['elgr_le_id' => 'le_id']
        );
    }

    /**
     * @return $this
     */
    public function getGroups() {
        return $this
            ->hasMany(
                Listgroup::className(),
                ['lg_id' => 'elgr_lg_id']
            )
            ->via('elGroups');
    }

    /**
     * @param bool $bWithFam
     * @return string
     */
    public function getFullname($bWithFam = true) {
        $s = trim(($bWithFam ? $this->le_fam : '') . ' ' . $this->le_name . ' ' . $this->le_otch);
        return $s;
    }

    /**
     * @param string $sDelim
     * @return string
     */
    public function getUsergroups($sDelim = ', ') {
        $s = implode($sDelim, ArrayHelper::map($this->groups, 'lg_id', 'lg_name'));
        return $s;
    }

    /**
     * @throws \yii\db\Exception
     */
    public function saveGroups() {
        $db = Yii::$app->db;

        // удаляем старые записи
        $db
            ->createCommand(
                'Update ' . Listelgr::tableName() . ' Set elgr_lg_id = 0, elgr_le_id = 0 Where elgr_le_id = :id',
                [':id' => $this->le_id]
            )
            ->execute();

        foreach($this->_allgroups As $grId) {
            // пробуем изменить пустую запись
            $nExec = $db
                ->createCommand(
                    strtolower(Yii::$app->db->driverName) != 'sqlite' ?
                        ('Update ' . Listelgr::tableName() . ' Set elgr_lg_id = :gid, elgr_le_id = :id Where elgr_lg_id = 0 And elgr_le_id = 0 Limit 1') :
                        ('Update ' . Listelgr::tableName() . ' Set elgr_lg_id = :gid, elgr_le_id = :id Where elgr_id In (Select elgr_id From ' . Listelgr::tableName() . ' Where elgr_lg_id = 0 And elgr_le_id = 0 Limit 1)'),
                    [
                        ':id' => $this->le_id,
                        ':gid' => $grId,
                    ]
                )
                ->execute();

            if( $nExec == 0 ) {
                // если не было пустой записи, то создаем новую
                $ogr = new Listelgr();
                $ogr->elgr_le_id = $this->le_id;
                $ogr->elgr_lg_id = $grId;
                if( !$ogr->save() ) {
                    Yii::error('Error save element groups: ' . print_r($ogr->getErrors(), true) . "\nattributes = " . print_r($ogr->attributes, true));
                }
            }
        }
    }

    /**
     * @throws \yii\db\Exception
     */
    public function saveGrouptags() {

        $db = Yii::$app->db;

        // удаляем старые записи
        $db
            ->createCommand(
                'Update ' . Listelgr::tableName() . ' Set elgr_lg_id = 0, elgr_le_id = 0 Where elgr_le_id = :id',
                [':id' => $this->le_id]
            )
            ->execute();

        foreach($this->_groupslist As $grName) {
            // пробуем изменить пустую запись

            $grId = Listgroup::getGroupIdByTitle($grName);
            Yii::info('Save group: ' . $grName . ' grId = ' . $grId);
            $nExec = $db
                ->createCommand(
                    strtolower(Yii::$app->db->driverName) != 'sqlite' ?
                        ('Update ' . Listelgr::tableName() . ' Set elgr_lg_id = :gid, elgr_le_id = :id Where elgr_lg_id = 0 And elgr_le_id = 0 Limit 1') :
                        ('Update ' . Listelgr::tableName() . ' Set elgr_lg_id = :gid, elgr_le_id = :id Where elgr_id In (Select elgr_id From ' . Listelgr::tableName() . ' Where elgr_lg_id = 0 And elgr_le_id = 0 Limit 1)'),
                    [
                        ':id' => $this->le_id,
                        ':gid' => $grId,
                    ]
                )
                ->execute();

            if( $nExec == 0 ) {
                // если не было пустой записи, то создаем новую
                $ogr = new Listelgr();
                $ogr->elgr_le_id = $this->le_id;
                $ogr->elgr_lg_id = $grId;
                if( !$ogr->save() ) {
                    Yii::error('Error save element groups: ' . print_r($ogr->getErrors(), true) . "\nattributes = " . print_r($ogr->attributes, true));
                }
            }
        }
    }
}
