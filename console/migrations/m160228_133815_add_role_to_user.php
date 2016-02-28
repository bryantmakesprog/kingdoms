<?php

use yii\db\Migration;

class m160228_133815_add_role_to_user extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'role', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('user', 'role');
    }
}
