<?php

use yii\db\Schema;
use yii\db\Migration;

class m161221_100011_schedule_schedule extends Migration
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
            '{{%schedule_schedule}}',
            [
                'id'=> $this->primaryKey(11),
                'owner_id'=> $this->integer(11)->notNull(),
                'target_model'=> $this->string(255)->notNull(),
                'target_id'=> $this->integer(11)->notNull(),
                'name'=> $this->string(255)->null()->defaultValue(null),
                'date'=> $this->datetime()->null()->defaultValue(null),
            ],$tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%schedule_schedule}}');
    }
}
