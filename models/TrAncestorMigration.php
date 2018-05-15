<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tr_ancestor_migration".
 *
 * @property string $id
 * @property string $ship
 * @property string $agent
 * @property string $port_embarkation
 * @property string $date_embarkation
 * @property string $port_disembarkation
 * @property string $date_disembarkation
 *
 * @property TrAncestor $trAncestor
 */
class TrAncestorMigration extends \yii\db\ActiveRecord
{
    const SCENARIO_CLIENT_CREATE = 'client_create';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tr_ancestor_migration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required', 'except' => self::SCENARIO_CLIENT_CREATE],
            [['id'], 'integer'],
            [['date_embarkation', 'date_disembarkation'], 'safe'],
            [['ship', 'agent', 'port_embarkation', 'port_disembarkation'], 'string', 'max' => 255],

            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => TrAncestor::className(), 'targetAttribute' => ['id' => 'id'], 'except' => self::SCENARIO_CLIENT_CREATE],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ship' => 'Name of the ship',
            'agent' => 'Particulars of the Agent',
            'port_embarkation' => 'Port of embarkation',
            'date_embarkation' => 'Date of embarkation',
            'port_disembarkation' => 'Port of disembarkation',
            'date_disembarkation' => 'Date when reached destination',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrAncestor()
    {
        return $this->hasOne(TrAncestor::className(), ['id' => 'id'])->inverseOf('trAncestorMigration');
    }

    function humanReadableInfo() {
        $aVal = [];

        $title = "Ancestor Migration #$this->id";
        $res = [$title=>["================== $title START =================="]];
        foreach ($this->attributes() as $attr) {
            $v = $this->$attr;
            if ( $v === null || $v === '' || in_array($attr, ['id']) )
                continue;

            $k = $this->getAttributeLabel($attr);
            $res[$title][$k] = (isset($aVal[$attr])) ? $aVal[$attr] : $v;
        }
        $res[$title][] = "================== $title END ==================";

        if ( count($res[$title]) == 2 ) {
            $res = [];
        }

        return $res;
    }

}
