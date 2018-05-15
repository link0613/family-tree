<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tr_ancestor_details".
 *
 * @property string $id
 * @property string $property
 * @property string $family_members
 * @property string $correspondence
 * @property string $stories
 * @property string $other
 *
 * @property TrAncestor $trAncestor
 */
class TrAncestorDetails extends \yii\db\ActiveRecord
{
    const SCENARIO_CLIENT_CREATE = 'client_create';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tr_ancestor_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required', 'except' => self::SCENARIO_CLIENT_CREATE],
            [['id'], 'integer'],
            [['property', 'family_members', 'correspondence', 'stories', 'other'], 'string'],

            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => TrAncestor::className(), 'targetAttribute' => ['id' => 'id'], 'except' => self::SCENARIO_CLIENT_CREATE],
            //defaults
            [['family_members'], 'default', 'value' => self::defaultFamilyMembers()],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'property' => 'Details of property owned by the migrant',
            'family_members' => 'Name of the Parents and immediate family members of the Migrant with nick names, if any',
            'correspondence' => 'Do you have copy of correspondence exchanged by the Migrant with his relations or others in India? If yes – give details',
            'stories' => 'Has the applicant heard any stories or details with regard to his Ancestors who migrated from India? If yes, give details',
            'other' => 'Additional information/documents that could be helpful in tracing the roots could also
be submitted along with the application.',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrAncestor()
    {
        return $this->hasOne(TrAncestor::className(), ['id' => 'id'])->inverseOf('trAncestorDetails');
    }

    static function defaultFamilyMembers() {
        return '- Father:
- Mother:
- Brothers:
- Sisters:
- Wife:
- Sons:
- Daughters:
- Paternal uncles/aunts:
- Maternal uncles/aunts:
- Others, please specify:';
    }

    function humanReadableInfo() {
        $aVal = [];

        $title = "Ancestor Details #$this->id";
        $res = [$title=>["================== $title START =================="]];
        foreach ($this->attributes() as $attr) {
            $v = $this->$attr;
            if ( $v === null || $v === '' || in_array($attr, ['id']) )
                continue;

            if ( $attr == 'family_members' && $v == self::defaultFamilyMembers() )
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
