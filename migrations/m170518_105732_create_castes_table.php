<?php

use yii\db\Migration;

/**
 * Handles the creation of table `castes`.
 */
class m170518_105732_create_castes_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('castes', [
            'id'             => $this->primaryKey(),
            'caste'          => $this->string(30)->defaultValue('Agarwal'),
            'gotra'          => $this->string(30),
            'original_gotra' => $this->string(30),
            'lord'           => $this->string(30),
            'saint'          => $this->string(30),
            'veda'           => $this->string(30),
            'branch'         => $this->string(30),
            'sutra'          => $this->string(30),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        echo " cannot be reverted.\n";
        return false;
        $this->dropTable('castes');
    }
}
