<?php

use yii\db\Schema;
use yii\db\Migration;

class m161221_100011_schedule_time extends Migration
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
            '{{%schedule_time}}',
            [
                'id'=> $this->primaryKey(11),
                'time'=> $this->string(5)->notNull(),
            ],$tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%schedule_time}}');
    }
}
