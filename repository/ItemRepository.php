<?php

namespace app\repository;

use app\models\Item;
use app\models\ItemRelations;
use Imagine\Image\Box;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\imagine\Image;
use yii\web\NotFoundHttpException;

class ItemRepository
{
    /**
     * Creates new Item and add relations to it
     *
     * @param int $userId
     * @param array $data
     * @return Item|bool
     * @throws NotFoundHttpException
     */
    public static function create($userId, array $data)
    {
        $dataAdditional = !empty($data['Additional']) ? $data['Additional'] : [];
        $dataItem = !empty($data['Item']) ? $data['Item'] : [];

        $data = array_merge($data, $dataAdditional, $dataItem, ['user_id' => $userId]);
        $dataItem = array_merge($dataItem, ['user_id' => $userId]);

        if (!isset($data['parent_id']) || is_null($data['parent_id']) || empty($data['parent_id'])) {
            throw new NotFoundHttpException('Not found parent user.');
        }

        /** @var Item $parentModel */
        $parentModel = Item::findOne([
            'id' => $data['parent_id'],
            'user_id' => $userId
        ]);

        if (!$parentModel) {
            throw new NotFoundHttpException('Not found user.');
        }

        $model = new Item($dataItem);

        // check if gender is empty
        $itemGender = !empty($data['Item']['gender']) ? $data['Item']['gender'] : null;
        if (is_null($itemGender)) {
            $itemNodeType = !empty($data['node_type']) ? $data['node_type'] : null;
            if ($itemNodeType == Item::NODE_TYPE_MOTHER) {
                $model->setGender(Item::GENDER_FEMALE);
            } elseif ($itemNodeType == Item::NODE_TYPE_FATHER) {
                $model->setGender(Item::GENDER_MALE);
            }
        }

        if (!$model->save()) {
            return false;
        }

        switch ($data['node_type']):
            case Item::NODE_TYPE_MOTHER :
            case Item::NODE_TYPE_FATHER :
                self::addParent($model, $parentModel);
                break;

            case Item::NODE_TYPE_SPOUSE_PARTNER :
                static::addSpouse($model, $parentModel);
                static::addSpouseChildren($model, $dataAdditional);
                break;

            case Item::NODE_TYPE_BROTHER :
            case Item::NODE_TYPE_SISTER:
                static::addBrotherOrSister($model, $parentModel, $dataAdditional);
                break;

            case Item::NODE_TYPE_DAUGHTER :
            case Item::NODE_TYPE_SON :
                self::addChild($model, $parentModel, $dataAdditional);
                break;
        endswitch;

        return $model;
    }

    /**
     * @param Item $model
     * @param Item $parentModel
     */
    protected static function addParent(Item $model, Item $parentModel)
    {
        if ($model->getGender() == Item::GENDER_MALE) {
            $parentModel->setFatherId($model->getId());
        } elseif ($model->getGender() == Item::GENDER_FEMALE) {
            $parentModel->setMotherId($model->getId());
        }
        $parentModel->save();

        // find marriage between parents
        if (!is_null($parentModel->getMotherId()) && !is_null($parentModel->getFatherId())) {
            $itemRelations = ItemRelations::find()
                ->where([
                    'item_id' => $parentModel->getFatherId(),
                    'relation_id' => $parentModel->getMotherId(),
                    'relation_type' => ItemRelations::RELATION_MARRIAGE
                ])
                ->orWhere([
                    'relation_id' => $parentModel->getFatherId(),
                    'item_id' => $parentModel->getMotherId(),
                    'relation_type' => ItemRelations::RELATION_MARRIAGE
                ])
                ->one();

            if (is_null($itemRelations)) {
                $itemRelations = new ItemRelations();
                $itemRelations->setItemId($parentModel->getFatherId());
                $itemRelations->setRelationId($parentModel->getMotherId());
                $itemRelations->setRelationType(ItemRelations::RELATION_MARRIAGE);
                $itemRelations->save();
            }
        }
    }

    /**
     * Set Father or Mother to model
     *
     * @param Item $model
     * @param Item $parentModel
     * @param array $additionalData
     */
    protected static function addChild(Item $model, Item $parentModel, array $additionalData = [])
    {
        $partnerId = null;
        if (isset($additionalData['marriage_child']) && isset($additionalData['mariage_with'])) {

            //  2. Find parental model partner
            $itemRelations = ItemRelations::find()
                ->where(['item_id' => $parentModel->getId()])
                ->orWhere(['relation_id' => $parentModel->getId()])
                ->all();

            $itemRelationsIds = [];

            /**@var ItemRelations $itemRelation */
            foreach ($itemRelations as $itemRelation) {
                if ($parentModel->getId() == $itemRelation->getItemId()) {
                    array_push($itemRelationsIds, $itemRelation->getRelationId());
                } else {
                    array_push($itemRelationsIds, $itemRelation->getItemId());
                }
            }

            // check if received partner id is in found partners ids
            if (!in_array($additionalData['mariage_with'], $itemRelationsIds)) {
                // partner is not found in previous relationships, create new relationship between 2 partners
                /** @var Item $partnerItem */
                $partnerItem = Item::findOne($additionalData['mariage_with']);

                // create new relation
                if (!is_null($partnerItem)) {
                    self::addSpouse($partnerItem, $parentModel);
                    $partnerId = $partnerItem->getId();
                }
            } else {
                $partnerId = $additionalData['mariage_with'];
            }
        }

        if ($parentModel->getGender() == Item::GENDER_MALE) {
            $model->setFatherId($parentModel->getId());
            $model->setMotherId($partnerId);
        } else {
            $model->setFatherId($partnerId);
            $model->setMotherId($parentModel->getId());
        }

        $model->save();
    }

    /**
     * @param Item $model
     * @param Item $parentModel
     */
    protected static function addSpouse(Item $model, Item $parentModel)
    {
        $itemRelation = new ItemRelations();
        $itemRelation->setItemId($model->getId());
        $itemRelation->setRelationId($parentModel->getId());
        $itemRelation->setRelationType(ItemRelations::RELATION_MARRIAGE);
        $itemRelation->save();
    }

    /**
     * @param Item $model
     * @param array $additionalData
     */
    protected static function addSpouseChildren(Item $model, array $additionalData = [])
    {
        if (isset($additionalData['partner_children'])) {
            $results = Item::find()
                ->where(['IN', 'id', $additionalData['partner_children']])
                ->all();

            /** @var Item $resultItem */
            foreach ($results as $resultItem) {
                if ($model->getGender() == Item::GENDER_FEMALE) {
                    $resultItem->setMotherId($model->getId());
                } elseif ($model->getGender() == Item::GENDER_MALE) {
                    $resultItem->setFatherId($model->getId());
                }

                if ($resultItem->validate()) {
                    $resultItem->save();
                }
            }
        }
    }

    /**
     * @param Item $model
     * @param Item $parentModel
     * @param array $additionalData
     */
    protected static function addBrotherOrSister(Item $model, Item $parentModel, array $additionalData = [])
    {
        //  if brother/sister is for separate parent
        $separateParentId = !empty($additionalData['mariage_with']) ? $additionalData['mariage_with'] : null;
        if (isset($additionalData['marriage_sibling']) && $additionalData['marriage_sibling'] == 'on'
            && !is_null($separateParentId)
        ) {
            if ($parentModel->getFatherId() == $separateParentId) {
                $model->setFatherId($separateParentId);
                $model->save();
            } elseif ($parentModel->getMotherId() == $separateParentId) {
                $model->setMotherId($separateParentId);
                $model->save();
            }
        } else {
            // if brother/sister is for common parents
            $model->setFatherId($parentModel->getFatherId());
            $model->setMotherId($parentModel->getMotherId());
            $model->save();
        }
    }

    /**
     * @param array|Item $item
     * @param bool $asJson
     * @return string|array
     */
    public static function initJsonItem($item, $asJson = true, $fullDate = true)
    {
        $data = [];

        if ($item instanceof Item) {
            $itemObject = $item;
        } elseif (is_numeric($item)) {
            $itemObject = Item::findOne(['id' => $item]);
        }

        if (is_null($itemObject)) {
            return $asJson ? Json::encode($data) : $data;
        }

        $default_image = [
            Item::GENDER_FEMALE => Url::to('images/woman.jpg', true),
            Item::GENDER_MALE => Url::to('images/man.jpg', true)
        ];

        //  get Image
        $image = $default_image[$itemObject->getGender()];
        $imageSrc = Url::to('images/man.jpg');
        if (!is_null($itemObject->getImage())) {
            $image = Url::to('images/profile/' . $itemObject->getImage(), true);
            $imageSrc = Url::to('images/profile/' . $itemObject->getImage());
        }

        //  get thumb
        $imageThumbnail = null;
        if (file_exists($imageSrc) and is_file($imageSrc) && $imageSrc != Url::to('images/man.jpg')) {

            $imageThumbnail = 'images/profile/id-' . $itemObject->getId() . '-thumb.jpg';

            Image::getImagine()
                ->open($imageSrc)
                ->thumbnail(new Box(40, 40))
                ->save(Yii::$app->basePath . '/web/' . $imageThumbnail, ['quality' => 90]);

            $imageThumbnail = Url::to($imageThumbnail, true);
        }

        //  set Item birth
        $birth = is_null($itemObject->getBirthDate()) ? null :
            ($fullDate ? date('d.m.Y', strtotime($itemObject->getBirthDate())) : date('Y', strtotime($itemObject->getBirthDate())));


        // set Item death & Item class
        $death = null;
        $class = $itemObject->getGender() ? 'man' : 'woman';
        $itemDeath = $itemObject->getDeath()->one();
        if (!is_null($itemDeath)) {
            $death = !is_null($itemDeath->getDate()) ?
				($fullDate ? date('d.m.Y', strtotime($itemDeath->getDate())) : date('Y', strtotime($itemDeath->getDate()))) : null;
            $class = $itemObject->getGender() ? 'man_dead' : 'woman_dead';
			if( $itemObject->getGender() ) {
				if( $image == Url::to("images/man.jpg", true) )
					$image = Url::to("images/{$class}.jpg", true);
			} else {
				if( $image == Url::to("images/woman.jpg", true) )
					$image = Url::to("images/{$class}.jpg", true);
			}
        }

        // get short name
        $shortName = (strlen($itemObject->getFirstName()) > 18) ? substr($itemObject->getFirstName(), 0, 15) . '...' :
            $itemObject->getFirstName();

        $data = [
            'id' => $itemObject->getId(),
            'gender' => $itemObject->getGender(),
            'email' => $itemObject->getEmail(),
            'name' => $itemObject->getFirstName(),
            'first_name' => $itemObject->getFirstName(),
            'middle_name' => $itemObject->getMiddleName(),
            'last_name' => $itemObject->getLastName(),
            'full_name' => $itemObject->getFullName(),
            'father_id' => $itemObject->getFatherId(),
            'mother_id' => $itemObject->getMotherId(),
            'image' => $image,
            'birth' => $birth,
            'death' => $death,
            'class' => $class,
            'extra' => [
                'shortName' => $shortName,
                'last_name' => $itemObject->getLastName(),
                'image' => $image,
                'imageThumbnail' => $imageThumbnail,
                'birth' => $birth,
                'death' => $death
            ]
        ];

        return $asJson ? Json::encode($data) : $data;
    }

    /**
     * @param int $userId
     * @param int $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getSpouses($userId, $id)
    {
        return Item::find()
            ->where([
                'user_id' => $userId,
                'parent_id' => $id,
                'node_type' => Item::NODE_TYPE_SPOUSE_PARTNER,
            ])
            ->all();
    }
}
