<?php

use yii\db\Schema;
use yii\db\Migration;

class m161203_094111_schedule_record extends Migration
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
            '{{%schedule_record}}',
            [
                'id'=> $this->primaryKey(11),
                'user_id'=> $this->integer(11)->notNull(),
                'schedule_id'=> $this->integer(11)->notNull(),
                'period_id'=> $this->integer(11)->notNull(),
                'status'=> $this->string()->notNull(),
            ],$tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%schedule_record}}');
    }
}
