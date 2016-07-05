<?php
namespace app\models;

use yii\base\Model;
use app\models\Listgroup;

class ElementGroupForm extends Model {
    public $el_id;
    public $el_groups;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['el_groups', ], 'required'], // 'userid',
            [['el_id', ], 'integer'],
            [['el_groups'], 'in', 'range'=>array_keys(Listgroup::getList()), 'allowArray' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userid' => 'Пользователь',
            'hostid' => 'Ресурс',
            'groups' => 'Группа',
        ];
    }

}