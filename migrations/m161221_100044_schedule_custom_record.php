<?php

use yii\db\Schema;
use yii\db\Migration;

class m161221_100044_schedule_custom_record extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(
            '{{%schedule_custom_record}}',
            [
                'id'=> $this->primaryKey(11),
                'name'=> $this->string(255)->notNull(),
                'text'=> $this->string(),
            ],$tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%schedule_custom_record}}');
    }
}
