<?php

use yii\db\Schema;
use yii\db\Migration;

class m161221_100011_schedule_user_to_schedule extends Migration
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
            '{{%schedule_user_to_schedule}}',
            [
                'id'=> $this->primaryKey(11),
                'user_id'=> $this->integer(11)->notNull(),
                'schedule_id'=> $this->integer(11)->notNull(),
            ],$tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%schedule_user_to_schedule}}');
    }
}
