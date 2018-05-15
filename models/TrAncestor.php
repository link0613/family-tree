<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tr_ancestor".
 *
 * @property string $id
 * @property string $applicant_id
 * @property string $name
 * @property integer $migrate_year
 * @property integer $migrate_age
 * @property string $caste_name_id
 * @property string $profession_id
 * @property string $state_id
 * @property string $district_id
 * @property string $district_sub_id
 * @property string $city_id
 * @property string $police_station
 * @property string $post_office
 * @property string $cities_neighbor
 *
 * @property CasteName $casteName
 * @property City $city
 * @property District $district
 * @property DistrictSub $districtSub
 * @property Profession $profession
 * @property State $state
 * @property TrApplicant $applicant
 * @property TrAncestorDetails $trAncestorDetails
 * @property TrAncestorMigration $trAncestorMigration
 */
class TrAncestor extends \yii\db\ActiveRecord
{
    const SCENARIO_CLIENT_CREATE = 'client_create';

    public $autoComplete_caste_name;
    public $autoComplete_profession;
    public $autoComplete_state;
    public $autoComplete_district;
    public $autoComplete_district_sub;
    public $autoComplete_city;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tr_ancestor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['applicant_id'], 'required', 'except' => self::SCENARIO_CLIENT_CREATE],
            [['applicant_id', 'caste_name_id', 'profession_id', 'state_id', 'district_id', 'district_sub_id', 'city_id'], 'integer'],
            [['name', 'police_station', 'post_office'], 'string', 'max' => 255],
            [['migrate_year'], 'integer', 'min' => 1000, 'max' => date('Y')],
            [['migrate_age'], 'integer', 'min' => 0, 'max' => 150],

            //BOF autoComplete
            [['autoComplete_district','autoComplete_district_sub','autoComplete_city'], 'safe'],
            [['autoComplete_state'], 'filter', 'skipOnEmpty' => true, 'filter' => $this->fnFromAutoComplete_setLocation()],
            [['autoComplete_caste_name'], 'filter', 'skipOnEmpty' => true, 'filter' => $this->fnFromAutoComplete_setCasteName()],
            [['autoComplete_profession'], 'filter', 'skipOnEmpty' => true, 'filter' => $this->fnFromAutoComplete_setProfession()],
            [['cities_neighbor'], 'filter', 'filter' => $this->fnRuleCitiesNeighbor()],
            //EOF autoComplete

            [['caste_name_id'], 'exist', 'skipOnError' => true, 'targetClass' => CasteName::className(), 'targetAttribute' => ['caste_name_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['district_id'], 'exist', 'skipOnError' => true, 'targetClass' => District::className(), 'targetAttribute' => ['district_id' => 'id']],
            [['district_sub_id'], 'exist', 'skipOnError' => true, 'targetClass' => DistrictSub::className(), 'targetAttribute' => ['district_sub_id' => 'id']],
            [['profession_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profession::className(), 'targetAttribute' => ['profession_id' => 'id']],
            [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['state_id' => 'id']],
            [['applicant_id'], 'exist', 'skipOnError' => true, 'targetClass' => TrApplicant::className(), 'targetAttribute' => ['applicant_id' => 'id'], 'except' => self::SCENARIO_CLIENT_CREATE],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'applicant_id' => 'Applicant ID',
            'name' => 'Name',
            'migrate_year' => 'Year when he/she migrated',
            'migrate_age' => 'Approximate age at the time of migration',
            'caste_name_id' => 'Caste',
            'profession_id' => 'Profession at the time of migration',
            'state_id' => 'Name of the State',
            'district_id' => 'Name of the Zila (District)',
            'district_sub_id' => 'Name of the Pargana/Tehsil',
            'city_id' => 'Name of the Village',
            'autoComplete_caste_name' => 'Caste',
            'autoComplete_profession' => 'Profession at the time of migration',
            'autoComplete_state' => 'Name of the State',
            'autoComplete_district' => 'Name of the Zila (District)',
            'autoComplete_district_sub' => 'Name of the Pargana/Tehsil',
            'autoComplete_city' => 'Name of the Village',
            'police_station' => 'Name of the Police Station',
            'post_office' => 'Name of the Post Office',
            'cities_neighbor' => 'Names of the Neighboring Villages',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCasteName()
    {
        return $this->hasOne(CasteName::className(), ['id' => 'caste_name_id'])->inverseOf('trAncestors');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id'])->inverseOf('trAncestors');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(District::className(), ['id' => 'district_id'])->inverseOf('trAncestors');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrictSub()
    {
        return $this->hasOne(DistrictSub::className(), ['id' => 'district_sub_id'])->inverseOf('trAncestors');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfession()
    {
        return $this->hasOne(Profession::className(), ['id' => 'profession_id'])->inverseOf('trAncestors');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'state_id'])->inverseOf('trAncestors');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicant()
    {
        return $this->hasOne(TrApplicant::className(), ['id' => 'applicant_id'])->inverseOf('trAncestors');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrAncestorDetails()
    {
        return $this->hasOne(TrAncestorDetails::className(), ['id' => 'id'])->inverseOf('id0');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrAncestorMigration()
    {
        return $this->hasOne(TrAncestorMigration::className(), ['id' => 'id'])->inverseOf('id0');
    }

    private function fnRuleCitiesNeighbor() {
        return function($v) {
            if ( !$this->district_sub_id || $this->hasErrors() )
                return null;

            $aCity = City::find()->indexBy('id')->asArray()->where([
                'district_sub_id' => $this->district_sub_id,
                'name'=> $v,
            ])->all();
            if ( empty($aCity) )
                return null;

            return $this->citiesNeighborFilter(array_keys($aCity));
        };
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

    private function fnFromAutoComplete_setCasteName() {
        return function() {
            $v = trim(filter_var(trim($this->autoComplete_caste_name, FILTER_SANITIZE_STRING)));
            if ( !$v )
                return;

            /** @var CasteName $model */
            $model = CasteName::findOne(['name' => $v]);
            if ( !$model ) {
                $model = new CasteName;
                $model->name = $v;
                if ( !$model->save() )
                    return;
            }
            $this->caste_name_id = $model->id;
            $this->populateRelation('casteName', $model);
        };
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

    /**
     * @return array|City[]
     */
    function findCitiesNeighbor() {
        $v = json_decode($this->cities_neighbor, true);
        if (empty($v))
            return [];

        return City::find()->indexBy('name')->where(['id' => $v])->all();
    }

    function humanReadableInfo() {
        $aVal = [
            'caste_name_id'   => ($this->caste_name_id) ? "[id=$this->caste_name_id] {$this->casteName->name}" : '',
            'profession_id'   => ($this->profession_id) ? "[id=$this->profession_id] {$this->profession->name}" : '',
            'state_id'        => ($this->state_id) ? "[id=$this->state_id] {$this->state->name}" : '',
            'district_id'     => ($this->district_id) ? "[id=$this->district_id] {$this->district->name}" : '',
            'district_sub_id' => ($this->district_sub_id) ? "[id=$this->district_sub_id] {$this->districtSub->name}" : '',
            'city_id'         => ($this->city_id) ? "[id=$this->city_id] {$this->city->name}" : '',
        ];

        $aCity = $this->findCitiesNeighbor();
        $aVal['cities_neighbor'] = (empty($aCity)) ? '' : join(', ', array_keys($aCity));

        $title = "Ancestor #$this->id";
        $res = [$title=>["================== $title START =================="]];
        foreach ($this->attributes() as $attr) {
            $v = $this->$attr;
            if ( $v === null || $v === '' || in_array($attr, ['applicant_id']) )
                continue;

            $k = $this->getAttributeLabel($attr);
            $res[$title][$k] = (isset($aVal[$attr])) ? $aVal[$attr] : $v;
        }
        $res[$title][] = "================== $title END ==================";

        if ( count($res[$title]) == 2 ) {
            $res = [];
        }

        //relations
        $res+= $this->trAncestorMigration->humanReadableInfo();
        $res+= $this->trAncestorDetails->humanReadableInfo();

        return $res;
    }

}
