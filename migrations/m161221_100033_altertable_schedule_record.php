<?php

use yii\db\Schema;
use yii\db\Migration;

class m161221_100033_altertable_schedule_record extends Migration
{
    public function up()
    {
        $this->addColumn('{{%schedule_record}}','client_model', $this->string(255)->null()->defaultValue(null));
    	$this->addColumn('{{%schedule_record}}','client_id', $this->integer(11));
    }

    public function down()
    {
        $this->dropColumn('{{%schedule_record}}', 'client_model');
        $this->dropColumn('{{%schedule_record}}', 'client_id');
    }
}
