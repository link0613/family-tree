<?php

namespace app\models;


/**
 * This is the model class for table "item_relations".
 *
 * @property integer $id
 * @property integer $item_id
 * @property integer $relation_id
 * @property integer $relation_type
 */
class ItemRelations extends \yii\db\ActiveRecord
{
    const RELATION_MARRIAGE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_relations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'item_id',
                    'relation_id',
                    'relation_type'
                ],
                'integer'
            ],
            [
                [
                    'item_id',
                    'relation_id',
                    'relation_type'
                ],
                'required'
            ],
            [
                [
                    'item_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => Item::className(),
                'targetAttribute' => ['item_id' => 'id']
            ],
            [
                [
                    'relation_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => Item::className(),
                'targetAttribute' => ['relation_id' => 'id']
            ],
        ];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $itemId
     *
     * @return ItemRelations
     */
    public function setItemId($itemId)
    {
        $this->item_id = $itemId;

        return $this;
    }

    /**
     * @return int
     */
    public function getItemId()
    {
        return $this->item_id;
    }

    /**
     * @param int $relationId
     *
     * @return ItemRelations
     */
    public function setRelationId($relationId)
    {
        $this->relation_id = $relationId;

        return $this;
    }

    /**
     * @return int
     */
    public function getRelationId()
    {
        return $this->relation_id;
    }

    /**
     * @param int $relationType
     *
     * @return ItemRelations
     */
    public function setRelationType($relationType)
    {
        $this->relation_type = $relationType;

        return $this;
    }
}