<?php

namespace app\components;

use app\models\Item;

/**
 * Class for working with family tree:
 *
 * prepare tree structure.
 *
 * @package app\components
 */
final class TreeBuilder
{
    public static function getHasPersonParents($authUser)
    {
        return self::hasPersonParentsArray($authUser);
    }

    private static function hasPersonParentsArray($authUser)
    {
        $persons = [];

        $data = Item::find()
            ->where(['user_id' => $authUser])
            ->asArray()
            ->all();

        foreach ($data as $person) {

            $persons[$person['id']][] = ($person['father_id'] != null || $person['mother_id'] != null) ? 1 : 0;

            $persons[$person['id']] = [
                'father' => null,
                'mother' => null
            ];

            $personFather = !is_null($person['father_id']) ? Item::findOne($person['father_id']) : null;
            $personMother = !is_null($person['mother_id']) ? Item::findOne($person['mother_id']) : null;

            if (!is_null($personFather)) {
                $persons[$person['id']]['father'] = [
                    'id' => $personFather->getId(),
                    'name' => $personFather->getFullName()
                ];
            }

            if (!is_null($personMother)) {
                $persons[$person['id']]['mother'] = [
                    'id' => $personMother->getId(),
                    'name' => $personMother->getFullName()
                ];
            }
        }
        return $persons;
    }
}
