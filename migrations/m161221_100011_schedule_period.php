<?php

use yii\db\Schema;
use yii\db\Migration;

class m161221_100011_schedule_period extends Migration
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
            '{{%schedule_period}}',
            [
                'id'=> $this->primaryKey(11),
                'schedule_id'=> $this->integer(11)->notNull(),
                'day_id'=> $this->integer(11)->notNull(),
                'time_start'=> $this->integer(11)->notNull(),
                'time_stop'=> $this->integer(11)->notNull(),
                'status'=> $this->string()->notNull(),
                'amount'=> $this->integer(11)->null()->defaultValue(null),
            ],$tableOptions
        );
        $this->createIndex('schedule_id','{{%schedule_period}}','schedule_id',false);
        $this->createIndex('time','{{%schedule_period}}','time_start,time_stop',false);
        $this->createIndex('time_stop','{{%schedule_period}}','time_stop',false);
    }

    public function safeDown()
    {
        $this->dropIndex('schedule_id', '{{%schedule_period}}');
        $this->dropIndex('time', '{{%schedule_period}}');
        $this->dropIndex('time_stop', '{{%schedule_period}}');
        $this->dropTable('{{%schedule_period}}');
    }
}
