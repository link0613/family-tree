<?php

use yii\db\Migration;

class m171119_140849_registrationEmailConfirmation extends Migration
{
    public function up()
    {
        $sql = "ALTER TABLE user ADD status TINYINT DEFAULT 0 NOT NULL;";
        Yii::$app->db->createCommand($sql)->execute();
    }

    public function down()
    {
        echo "m171119_140849_registrationEmailConfirmation cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
