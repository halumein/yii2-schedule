<?php

use yii\db\Migration;

class m170726_110046_add_column_to_schedule_record_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('schedule_record', 'amount', $this->integer()->defaultValue(1)->notNull());
    }

    public function safeDown()
    {
        $this->dropColumn('schedule_record', 'amount');
    }

}
