<?php

use yii\db\Schema;
use yii\db\Migration;

class m161221_100011_schedule_record extends Migration
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
                'status'=> $this->string()->notNull()->defaultValue('in process'),
            ],$tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropIndex('user_id', '{{%schedule_record}}');
        $this->dropTable('{{%schedule_record}}');
    }
}
