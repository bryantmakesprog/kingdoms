<?php

use yii\db\Migration;

class m160228_181324_create_table_kingdom extends Migration
{
    public function up()
    {
        $this->createTable('{{%kingdom}}', [
            'id' => $this->primaryKey(),
            'user' => $this->integer()->notNull()->unique(),
            'points' => $this->double()->notNull()->defaultValue(50.0),
            'economy' => $this->double()->notNull()->defaultValue(0.0),
            'loyalty' => $this->double()->notNull()->defaultValue(0.0),
            'stability' => $this->double()->notNull()->defaultValue(0.0),
            'unrest' => $this->double()->notNull()->defaultValue(0.0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%kingdom}}');
    }
}
