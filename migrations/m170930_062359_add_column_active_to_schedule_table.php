<?php

use yii\db\Migration;

class m170930_062359_add_column_active_to_schedule_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('schedule_schedule', 'active', $this->boolean());
    }

    public function safeDown()
    {
        $this->dropColumn('schedule_schedule', 'active');
    }

}
