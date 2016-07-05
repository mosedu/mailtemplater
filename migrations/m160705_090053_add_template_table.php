<?php

use yii\db\Schema;
use app\components\Migration;

class m160705_090053_add_template_table extends Migration
{
    public function up()
    {
        $tableOptionsMyISAM = '';
        $b = false;
        if( strtolower(Yii::$app->db->driverName) != 'sqlite' ) {
            $tableOptionsMyISAM = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
            $b = true;
        }

        $this->createTable('{{%mailtempl}}', [
            'mt_id' => Schema::TYPE_PK . ($b ? ' Comment \'Id\'' : ''),
            'mt_createtime' => Schema::TYPE_DATETIME . ($b ? ' Comment \'Создан\'' : ''),
            'mt_name' => Schema::TYPE_STRING . ($b ? ' Comment \'Имя\'' : ''),
            'mt_text' => Schema::TYPE_TEXT . ($b ? ' Comment \'Текст\'' : ''),
        ], $tableOptionsMyISAM);

        $this->createIndex('idx_mt_name', '{{%mailtempl}}', 'mt_name');

        // need refrash cache after change table strusture
        $this->refreshCache();
    }

    public function down()
    {
//        echo "m160705_090053_add_template_table cannot be reverted.\n";

        $this->dropTable('{{%mailtempl}}');
        // need refresh cache after change table strusture
        $this->refreshCache();

//        return false;
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
