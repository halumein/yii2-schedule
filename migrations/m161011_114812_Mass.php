<?php

use yii\db\Schema;
use yii\db\Migration;

class m161011_114812_Mass extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';
        $transaction=$this->db->beginTransaction();
        try{
             $this->createTable('{{%schedule_period}}',[
               'id'=> $this->primaryKey(11),
               'owner_id'=> $this->integer(11)->notNull(),
               'target_model'=> $this->string(255)->notNull(),
               'target_id'=> $this->integer(11)->notNull(),
               'monday'=> $this->text()->null()->defaultValue(null),
               'tuesday'=> $this->text()->null()->defaultValue(null),
               'wednesday'=> $this->text()->null()->defaultValue(null),
               'thursday'=> $this->text()->null()->defaultValue(null),
               'friday'=> $this->text()->null()->defaultValue(null),
               'saturday'=> $this->text()->null()->defaultValue(null),
               'sunday'=> $this->text()->null()->defaultValue(null),
            ], $tableOptions);
            $transaction->commit();
        } catch (Exception $e) {
             echo 'Catch Exception '.$e->getMessage().' and rollBack this';
             $transaction->rollBack();
        }
    }

    public function safeDown()
    {
        $transaction=$this->db->beginTransaction();
        try{
            $this->dropTable('{{%schedule_period}}');
            $transaction->commit();
        } catch (Exception $e) {
            echo 'Catch Exception '.$e->getMessage().' and rollBack this';
            $transaction->rollBack();
        }
    }
}
