<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "castes".
 *
 * @property int $id
 * @property string $caste
 * @property string $gontra
 * @property string $original_gontra
 * @property string $lord
 * @property string $saint
 * @property string $veda
 * @property string $branch
 * @property string $sutra
 */
class Caste extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'castes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id' , 'caste' , 'gotra' , 'original_gontra' , 'lord' , 'saint' , 'veda' , 'branch' , 'sutra'], 'required'],
            [['caste' , 'gotra' , 'original_gotra' , 'lord' , 'saint' , 'veda' , 'branch' , 'sutra'], 'string', 'max' => 30],
            [['gontra'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'              => 'Id',
            'caste'           => 'Caste',
            'gotra'           => 'Gotra',
            'original_gotra'  => 'Original Gotra',
            'lord'            => 'Lord',
            'saint'           => 'Saint (Guru)',
            'veda'            => 'Veda',
            'branch'          => 'Branch',
            'sutra'           => 'Sutra',
        ];
    }
}
