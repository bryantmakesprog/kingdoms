<?php

use yii\db\Migration;

class m160228_133911_create_table_unit_connections extends Migration
{
    public function up()
    {
        $this->createTable('{{%unitConnections}}', [
            'id' => $this->primaryKey(),
            'army' => $this->integer()->notNull(),
            'unit' => $this->integer()->notNull(),
            'count' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%unitConnections}}');
    }
}
