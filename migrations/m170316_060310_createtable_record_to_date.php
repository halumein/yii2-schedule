<?php

use yii\db\Migration;

class m170316_060310_createtable_record_to_date extends Migration
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
            '{{%schedule_record_to_date}}',
            [
                'id'=> $this->primaryKey(11),
                'record_id'=> $this->integer(11)->notNull(),
                'date'=> $this->datetime()->notNull()->defaultValue(null),
                'description'=> $this->string(255)->null()->defaultValue(null),
            ],$tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%schedule_schedule}}');
    }
}
