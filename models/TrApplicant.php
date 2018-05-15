<?php

namespace app\models;

use app\components\interfaces\EmailConfirmed;
use Yii;

/**
 * This is the model class for table "tr_applicant".
 *
 * @property string $id
 * @property int $status
 * @property string $name
 * @property string $contact_address
 * @property string $contact_phone
 * @property string $contact_fax
 * @property string $contact_email
 * @property string $profession_id
 * @property string $father
 * @property string $mother
 * @property string $grandfather
 * @property string $grandmother
 * @property string $great_grandfather
 * @property string $great_grandmother
 *
 * @property TrAncestor[] $trAncestors
 * @property Profession $profession
 */
class TrApplicant extends \yii\db\ActiveRecord implements EmailConfirmed
{
    const STATUS_unverified = 0;
    const STATUS_active = 1;

    public $autoComplete_profession;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tr_applicant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'contact_address', 'contact_phone', 'contact_email'], 'required'],
            [['profession_id'], 'integer'],
            [['name', 'contact_address', 'contact_phone', 'contact_fax', 'contact_email', 'father', 'mother', 'grandfather', 'grandmother', 'great_grandfather', 'great_grandmother'], 'string', 'max' => 255],
            [['contact_email'], 'email'],

            //BOF autoComplete
            [['autoComplete_profession'], 'filter', 'skipOnEmpty' => true, 'filter' => $this->fnFromAutoComplete_setProfession()],
            //EOF autoComplete

            [['profession_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profession::className(), 'targetAttribute' => ['profession_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name of the Applicant',
            'contact_address' => 'Complete postal address',
            'contact_phone' => 'Telephone No.',
            'contact_fax' => 'Fax No.',
            'contact_email' => 'E-mail',
            'profession_id' => 'Profession',
            'autoComplete_profession' => 'Profession',
            'father' => 'Father',
            'mother' => 'Mother',
            'grandfather' => 'Grandfather',
            'grandmother' => 'Grandmother',
            'great_grandfather' => 'Great Grandfather',
            'great_grandmother' => 'Great Grandmother',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrAncestors()
    {
        return $this->hasMany(TrAncestor::className(), ['applicant_id' => 'id'])->inverseOf('applicant');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfession()
    {
        return $this->hasOne(Profession::className(), ['id' => 'profession_id'])->inverseOf('trApplicants');
    }

    static function emailConfirmed($email) {
        return self::updateAll(['status' => self::STATUS_active], 'contact_email = :email', [':email' => $email]);
    }

    private function fnFromAutoComplete_setProfession() {
        return function() {
            $v = trim(filter_var(trim($this->autoComplete_profession, FILTER_SANITIZE_STRING)));
            if ( !$v )
                return;

            /** @var Profession $model */
            $model = Profession::findOne(['name' => $v]);
            if ( !$model ) {
                $model = new Profession;
                $model->name = $v;
                if ( !$model->save() )
                    return;
            }
            $this->profession_id = $model->id;
            $this->populateRelation('profession', $model);
        };
    }
    
    function getUid() {
        return $this->tableName().'_'.$this->id;
    }

    function humanReadableInfo() {
        $aExtVal = [
            'profession_id'   => ($this->profession_id) ? "[id=$this->profession_id] {$this->profession->name}" : '',
        ];

        // itself
        $title = "Applicant #$this->id";
        $res = [$title=>["================== $title START =================="]];
        foreach ($this->attributes() as $attr) {
            $v = $this->$attr;
            if ( $v === null || $v === '' || $attr == 'status' )
                continue;

            $k = $this->getAttributeLabel($attr);
            $res[$title][$k] = (isset($aExtVal[$attr])) ? $aExtVal[$attr] : $v;
        }
        $res[$title][] = "================== $title END ==================";

        //relations
        foreach ($this->trAncestors as $trAncestor) {
            $res+= $trAncestor->humanReadableInfo();
        }

        return $res;
    }

}
