<?php

use yii\db\Schema;
use app\components\Migration;

class m160714_133437_add_letter_table extends Migration
{
    public function up()
    {
        $tableOptionsMyISAM = '';
        $b = false;
        if( strtolower(Yii::$app->db->driverName) != 'sqlite' ) {
            $tableOptionsMyISAM = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
            $b = true;
        }

        $this->createTable('{{%letter}}', [
            'let_id' => Schema::TYPE_PK . ($b ? ' Comment \'Id\'' : ''),
            'let_createtime' => Schema::TYPE_DATETIME . ($b ? ' Comment \'Создано\'' : ''),
            'let_sendtime' => Schema::TYPE_DATETIME . ($b ? ' Comment \'Отправлено\'' : ''),
            'let_mt_id' => Schema::TYPE_INTEGER . ($b ? ' Comment \'Шаблон\'' : ''),
            'let_subject' => Schema::TYPE_STRING . ($b ? ' Comment \'Тема письма\'' : ''),
            'let_text' => Schema::TYPE_TEXT . ($b ? ' Comment \'Текст письма\'' : ''),
            'let_us_id' => Schema::TYPE_INTEGER . ($b ? ' Comment \'Отправитель\'' : ''),
            'let_send_id' => Schema::TYPE_INTEGER . ($b ? ' Comment \'id рассылки\'' : ''),
            'let_state' => Schema::TYPE_INTEGER . ($b ? ' Comment \'Флаг состояния\'' : ''),
            'let_send_num' => Schema::TYPE_INTEGER . ($b ? ' Comment \'Процент отправки\'' : ''),
        ], $tableOptionsMyISAM);

        $this->createIndex('idx_let_mt_id', '{{%letter}}', 'let_mt_id');
        $this->createIndex('idx_let_us_id', '{{%letter}}', 'let_us_id');
        $this->createIndex('idx_let_send_id', '{{%letter}}', 'let_send_id');
        $this->createIndex('idx_let_state', '{{%letter}}', 'let_state');

        $this->refreshCache();
    }

    public function down()
    {
        $this->dropTable('{{%letter}}');
        $this->refreshCache();
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
