<?php

use yii\db\Migration;

class m160228_133852_create_table_army extends Migration
{
    public function up()
    {
        $this->createTable('{{%army}}', [
            'id' => $this->primaryKey(),
            'user' => $this->integer()->notNull(),
            'kingdom' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%army}}');
    }
}
