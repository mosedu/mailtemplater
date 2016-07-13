<?php

use yii\db\Schema;
use app\components\Migration;

class m160705_111945_add_list_tables extends Migration
{
    public function up()
    {
        $tableOptionsMyISAM = '';
        $b = false;
        if( strtolower(Yii::$app->db->driverName) != 'sqlite' ) {
            $tableOptionsMyISAM = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
            $b = true;
        }

        $this->createTable('{{%listgroup}}', [
            'lg_id' => Schema::TYPE_PK . ($b ? ' Comment \'Id\'' : ''),
            'lg_createtime' => Schema::TYPE_DATETIME . ($b ? ' Comment \'Создан\'' : ''),
            'lg_name' => Schema::TYPE_STRING . ($b ? ' Comment \'Наименование\'' : ' COLLATE NOCASE'),
        ], $tableOptionsMyISAM);

        $this->createIndex('idx_lg_name', '{{%listgroup}}', 'lg_name');

        $this->createTable('{{%listelement}}', [
            'le_id' => Schema::TYPE_PK . ($b ? ' Comment \'Id\'' : ''),
            'le_createtime' => Schema::TYPE_DATETIME . ($b ? ' Comment \'Создан\'' : ''),
            'le_email' => Schema::TYPE_STRING . ($b ? ' Comment \'Email\'' : ''),
            'le_fam' => Schema::TYPE_STRING . ($b ? ' Comment \'Фамилия\'' : ''),
            'le_name' => Schema::TYPE_STRING . ($b ? ' Comment \'Имя\'' : ''),
            'le_otch' => Schema::TYPE_STRING . ($b ? ' Comment \'Отчество\'' : ''),
            'le_org' => Schema::TYPE_STRING . ($b ? ' Comment \'Организация\'' : ''),
        ], $tableOptionsMyISAM);

        $this->createIndex('idx_le_org', '{{%listelement}}', 'le_org');
        $this->createIndex('idx_le_email', '{{%listelement}}', 'le_email');

        $this->createTable('{{%listelgr}}', [
            'elgr_id' => Schema::TYPE_PK . ($b ? ' Comment \'Id\'' : ''),
            'elgr_le_id' => Schema::TYPE_INTEGER . ($b ? ' Comment \'Элемент\'' : ''),
            'elgr_lg_id' => Schema::TYPE_INTEGER . ($b ? ' Comment \'Группа\'' : ''),
        ], $tableOptionsMyISAM);

        $this->createIndex('idx_elgr_lg_id', '{{%listelgr}}', 'elgr_lg_id');
        $this->createIndex('idx_elgr_le_id', '{{%listelgr}}', 'elgr_le_id');

        // need refrash cache after change table strusture
        $this->refreshCache();
    }

    public function down()
    {
        $this->dropTable('{{%listelgr}}');
        $this->dropTable('{{%listelement}}');
        $this->dropTable('{{%listgroup}}');
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
