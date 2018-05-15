<?php

namespace app\models;

use app\components\interfaces\EmailConfirmed;
use Yii;

/**
 * This is the model class for table "researcher".
 *
 * @property string $id
 * @property integer $status
 * @property string $name
 * @property string $email
 * @property string $contact_phone
 * @property string $contact_skype
 * @property string $contact_whats_app
 * @property string $state_id
 * @property string $district_id
 * @property string $district_sub_id
 * @property string $city_id
 * @property string $custom_location
 * @property string $address
 * @property string $email_paypal
 * @property string $linked_in
 * @property string $business
 * @property string $website
 * @property integer $service
 * @property string $extra_1
 * @property string $extra_2
 * @property string $extra_3
 * @property string $extra_4
 * @property string $extra_5
 * @property string $extra_6
 * @property string $extra_7
 * @property string $extra_8
 * @property string $extra_9
 * @property string $extra_10
 *
 * @property City $city
 * @property District $district
 * @property DistrictSub $districtSub
 * @property State $state
 */
class Researcher extends \yii\db\ActiveRecord implements EmailConfirmed
{
    const STATUS_unverified = 0;
    const STATUS_active = 1;

    const SERVICE_look_ups = 1;
    const SERVICE_research = 2;
    const SERVICE_both     = 3;

    public $autoComplete_state;
    public $autoComplete_district;
    public $autoComplete_district_sub;
    public $autoComplete_city;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'researcher';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state_id', 'district_id', 'district_sub_id', 'city_id', 'service'], 'integer'],
            [['name', 'email', 'contact_phone'], 'required'],
            [['name', 'email', 'contact_phone', 'contact_skype', 'contact_whats_app', 'custom_location', 'address', 'email_paypal', 'linked_in', 'business', 'website', 'extra_1', 'extra_2', 'extra_3', 'extra_4', 'extra_5', 'extra_6', 'extra_7', 'extra_8', 'extra_9', 'extra_10'], 'string', 'max' => 255],
            [['email', 'email_paypal'], 'email'],
            [['linked_in', 'website'], 'url'],
            [['service'], 'in', 'range' => array_keys(self::serviceList())],

            //BOF autoComplete
            [['autoComplete_district','autoComplete_district_sub','autoComplete_city'], 'safe'],
            [['autoComplete_state'], 'filter', 'skipOnEmpty' => true, 'filter' => $this->fnFromAutoComplete_setLocation()],
            //EOF autoComplete

            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['district_id'], 'exist', 'skipOnError' => true, 'targetClass' => District::className(), 'targetAttribute' => ['district_id' => 'id']],
            [['district_sub_id'], 'exist', 'skipOnError' => true, 'targetClass' => DistrictSub::className(), 'targetAttribute' => ['district_sub_id' => 'id']],
            [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['state_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => '0=email not verified',
            'name' => 'Name',
            'email' => 'Email address',
            'contact_phone' => 'Phone number',
            'contact_skype' => 'Skype name',
            'contact_whats_app' => 'WhatsApp number',
            'state_id' => 'Name of the State',
            'district_id' => 'Name of the Zila (District)',
            'district_sub_id' => 'Name of the Pargana/Tehsil',
            'city_id' => 'Name of the Village',
            'autoComplete_state' => 'Name of the State',
            'autoComplete_district' => 'Name of the Zila (District)',
            'autoComplete_district_sub' => 'Name of the Pargana/Tehsil',
            'autoComplete_city' => 'Name of the Village',
            'custom_location' => 'Location, different from India',
            'address' => 'Complete address',
            'email_paypal' => 'PayPal email address',
            'linked_in' => 'Please provide a link to your LinkedIn page (if you have one).',
            'business' => 'Do you own your own genealogy business? If so, what is the name of your business.',
            'website' => 'Please provide a link to your website (if you have one)',
            'service' => 'Please tell us which of the following services you provide:',
            'extra_1' => 'Extra 1',
            'extra_2' => 'Extra 2',
            'extra_3' => 'Extra 3',
            'extra_4' => 'Extra 4',
            'extra_5' => 'Extra 5',
            'extra_6' => 'Extra 6',
            'extra_7' => 'Extra 7',
            'extra_8' => 'Extra 8',
            'extra_9' => 'Extra 9',
            'extra_10' => 'Extra 10',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id'])->inverseOf('researchers');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(District::className(), ['id' => 'district_id'])->inverseOf('researchers');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrictSub()
    {
        return $this->hasOne(DistrictSub::className(), ['id' => 'district_sub_id'])->inverseOf('researchers');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'state_id'])->inverseOf('researchers');
    }

    static function serviceList() {
        return [
            self::SERVICE_look_ups => 'I am only interested in assisting with local look-ups, including record pick-ups at local repositories and local emetery photos. I do not wish to perform conduct any research beyond these basic tasks.',
            self::SERVICE_research => 'I am only interested in conducting research, and do not wish to perform local look-ups.',
            self::SERVICE_both => 'I am interested in conducting both local look-ups and research.',
        ];
    }

    static function emailConfirmed($email) {
        return self::updateAll(['status' => self::STATUS_active], 'email = :email', [':email' => $email]);
    }

    function getUid() {
        return $this->tableName().'_'.$this->id;
    }

    function humanReadableInfo() {
        $aVal = [
            'state_id'        => ($this->state_id) ? "[id=$this->state_id] {$this->state->name}" : '',
            'district_id'     => ($this->district_id) ? "[id=$this->district_id] {$this->district->name}" : '',
            'district_sub_id' => ($this->district_sub_id) ? "[id=$this->district_sub_id] {$this->districtSub->name}" : '',
            'city_id'         => ($this->city_id) ? "[id=$this->city_id] {$this->city->name}" : '',
            'service'         => ($this->service) ? "[id=$this->service] {$this->serviceList()[$this->service]}" : '',
        ];

        $res = [];
        foreach ($this->attributes() as $attr) {
            $v = $this->$attr;
            if ( $v === null || $v === '' || $attr == 'status' )
                continue;

            $k = $this->getAttributeLabel($attr);
            $res[$k] = (isset($aVal[$attr])) ? $aVal[$attr] : $v;
        }

        return $res;
    }



    private function citiesNeighborFilter($v) {
        $res = json_encode($v);
        if ( strlen($res) > 255 ) {
            array_pop($v);
            return $this->citiesNeighborFilter($v);
        }
        return $res;
    }

    private function fnFromAutoComplete_setLocation() {
        return function() {
            //BOF state
            $v = trim(filter_var(trim($this->autoComplete_state, FILTER_SANITIZE_STRING)));
            if ( !$v )
                return;

            /** @var State $state */
            $state = State::findOne(['name' => $v]);
            if ( !$state ) {
                $state = new State;
                $state->name = mb_strtoupper($v, 'utf8');
                if ( !$state->save() )
                    return;
            }
            $this->state_id = $state->id;
            $this->populateRelation('state', $state);
            //EOF state

            //BOF district
            $v = trim(filter_var(trim($this->autoComplete_district, FILTER_SANITIZE_STRING)));
            if ( !$v )
                return;

            /** @var District $district */
            $district = District::findOne([
                'state_id' => $this->state_id,
                'name' => $v,
            ]);
            if ( !$district ) {
                $district = new District;
                $district->state_id = $this->state_id;
                $district->name = $v;
                if ( !$district->save() )
                    return;
            }
            $this->district_id = $district->id;
            $this->populateRelation('district', $district);
            //EOF district

            //BOF district_sub
            $v = trim(filter_var(trim($this->autoComplete_district_sub, FILTER_SANITIZE_STRING)));
            if ( !$v )
                return;

            /** @var DistrictSub $districtSub */
            $districtSub = DistrictSub::findOne([
                'district_id' => $this->district_id,
                'name' => $v,
            ]);
            if ( !$districtSub ) {
                $districtSub = new DistrictSub;
                $districtSub->district_id = $this->district_id;
                $districtSub->name = $v;
                if ( !$districtSub->save() )
                    return;
            }
            $this->district_sub_id = $districtSub->id;
            $this->populateRelation('districtSub', $districtSub);
            //EOF district_sub


            //BOF city
            $v = trim(filter_var(trim($this->autoComplete_city, FILTER_SANITIZE_STRING)));
            if ( !$v )
                return;

            /** @var City $city */
            $city = City::findOne([
                'district_sub_id' => $this->district_sub_id,
                'name' => $v,
            ]);
            if ( !$city ) {
                $city = new City;
                $city->district_sub_id = $this->district_sub_id;
                $city->name = $v;
                if ( !$city->save() )
                    return;
            }
            $this->city_id = $city->id;
            $this->populateRelation('city', $city);
            //EOF city
        };
    }

}
