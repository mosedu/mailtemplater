<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class TemplatesendForm extends Model
{
    public $subject;
    public $groupid;
    public $templateid;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['subject', 'groupid', 'templateid', ], 'required'],
            [['subject', ], 'string', 'max' => 255],
            [['groupid', 'templateid', ], 'integer', ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'subject' => 'Тема письма',
            'groupid' => 'Группа списка рассылки',
            'templateid' => 'Шаблон письма',
        ];
    }


}
