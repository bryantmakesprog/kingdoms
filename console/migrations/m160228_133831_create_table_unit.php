<?php

use yii\db\Migration;

class m160228_133831_create_table_unit extends Migration
{
    public function up()
    {
        $this->createTable('{{%unit}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'description' => $this->text(),
            'level' => $this->integer()->notNull(),
            'hitDice' => $this->integer()->notNull(),
            'isRanged' => $this->boolean()->notNull()->defaultValue(0),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%unit}}');
    }
}
