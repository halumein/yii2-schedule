<?php

use yii\db\Migration;

class m170711_083838_alter_table_time extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%schedule_time}}','show', $this->smallInteger()->null()->defaultValue(1));
        $this->addColumn('{{%schedule_time}}','sort', $this->integer(10)->null()->defaultValue(null));

    }

    public function safeDown()
    {
        $this->dropColumn('{{%schedule_time}}', 'client_model');
        $this->dropColumn('{{%schedule_time}}', 'client_model');

    }
}
