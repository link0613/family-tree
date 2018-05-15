<?php

namespace tests\codeception\unit;

use app\components\TreeBuilder;
use app\repository\ItemRepository;

use Codeception\Util\Debug;
use Yii;
use yii\codeception\TestCase;
use Codeception\Specify;

class TreeBuilderTest extends TestCase
{
    /** @var TreeBuilder */
    private $builder;

    public function setUp()
    {
        parent::setUp();

        $this->builder = new TreeBuilder();
    }

    /** @test */
    public function can_properly_build_simple_tree()
    {
        $fixture = $this->getSimpleFixture();

//        Debug::debug($fixture);

        $result = $this->builder->build($fixture);

//        Debug::debug($result);

        $this->assertCount(1, $result, 'Expected one root element.');
        $this->assertEquals('Alex', $result[0]['first_name'], 'Unexpected element.');
    }

    /** @test */
    public function can_properly_sort_root_elements_in_tree()
    {
        $fixture = $this->getTwoNodesWithDifferentLevelsSimpleFixture();

//        Debug::debug($fixture);

        $result = $this->builder->build($fixture);

//        Debug::debug($result);

        $this->assertCount(2, $result, 'Expected two root elements.');
        $this->assertEquals('Alex', $result[0]['children'][0]['first_name'], 'Unexpected element.');
        $this->assertEquals('Kazymyr', $result[1]['first_name'], 'Unexpected element.');
    }

    /** @test */
    public function can_properly_add_extra_tree_keys_to_extended_elements()
    {
        $fixture = $this->getTwoNodesWithDifferentLevelsSimpleFixture();

//        Debug::debug($fixture);

        $result = $this->builder->build($fixture);

//        Debug::debug($result);

        $this->assertCount(2, $result, 'Expected two root elements.');
        $this->assertEquals('Iryna', $result[0]['children'][0]['marriages'][0]['spouse']['first_name'], "Unexpected element.");
        $this->assertArrayHasKey('extraTree', $result[0]['children'][0]['marriages'][0]['spouse']['extra'], "Not found expected 'extraTree' element for the node.");
        $this->assertTrue($result[0]['children'][0]['marriages'][0]['spouse']['extra']['extraTree']);
    }

    /** @test */
    public function can_properly_build_tree_with_children_without_marriage()
    {
        $fixture = $this->getChildrenWithoutMarriageFixture();

//        Debug::debug($fixture);

        $result = $this->builder->build($fixture);

//        Debug::debug($result);

        $this->assertCount(1, $result, 'Expected one root element.');
        $this->assertArrayHasKey(
            'first_name', $result[0]['marriages'][0]['children'][0]['children'][0], 'Children not found.'
        );
    }

    /** @test */
    public function can_properly_build_tree_with_empty_hidden_nodes()
    {
        $fixture = $this->getTwoNodesWithDifferentLevelsSimpleFixture();

        //Debug::debug($fixture);

        $result = $this->builder->build($fixture);

        //Debug::debug($result);

        $this->assertCount(2, $result, 'Expected two root elements.');
        $this->assertArrayHasKey('hidden', $result[0], 'Expected two root elements.');
    }

    protected function getTwoNodesWithDifferentLevelsSimpleFixture()
    {
        $n = $this->getNodes();

        $result = [
            $n['grand_father_by_mother']['id'] => $n['grand_father_by_mother'],
            $n['father_without_parents']['id'] => $n['father_without_parents'],
            $n['mother_with_father']['id'] => $n['mother_with_father'],
            $n['root']['id'] => $n['root'],
            //$n[''], $n[''],
        ];


        return $result;
    }

    protected function getTwoNodesWithDifferentLevelsFixture()
    {
        $n = $this->getNodes();

        return [
            $n['great_grand_father']['id'] => $n['great_grand_father'],
            $n['grand_father']['id'] => $n['grand_father'],
            $n['grand_mother']['id'] => $n['grand_mother'],
            $n['father_with_marriage_and_children']['id'] => $n['father_with_marriage_and_children'],
            $n['grand_father_by_mother']['id'] => $n['grand_father_by_mother'],
            $n['mother']['id'] => $n['mother'],
            $n['wife']['id'] => $n['wife'],
            $n['root_with_marriage_and_children']['id'] => $n['root_with_marriage_and_children'],
            $n['son']['id'] => $n['son'],
            //$n[''], $n[''],
        ];
    }

    protected function getChildrenWithoutMarriageFixture()
    {
        $n = $this->getNodes();

        return [
            $n['grand_father']['id'] => $n['grand_father'],
            $n['grand_mother']['id'] => $n['grand_mother'],
            $n['father_without_marriage']['id'] => $n['father_without_marriage'],
            $n['root']['id'] => $n['root'],
            //$n[''], $n[''],
        ];
    }

    protected function getSimpleFixture()
    {
        $n = $this->getNodes();

        return [
            $n['father_without_parents']['id'] => $n['father_without_parents'],
            $n['mother']['id'] => $n['mother'],
            $n['root']['id'] => $n['root'],
            //$n[''], $n[''],
        ];
    }

    /** @test */
    public function can_properly_build_tree_reference_to_some_id()
    {
        $fixture = $this->getTwoNodesWithDifferentLevelsAndMothersSonFixture();

        Debug::debug($fixture);

        $rootId = 900;

        $result = $this->builder->build($fixture, $rootId);

        Debug::debug($result);

        $this->assertCount(1, $result, 'Expected one root element.');
        $this->assertArrayHasKey(
            'first_name', $result[0]['marriages'][0]['children'][0]['children'][0], 'Children not found.'
        );
    }

    protected function getTwoNodesWithDifferentLevelsAndMothersSonFixture()
    {
        $n = $this->getNodes();

        $result = [
            $n['grand_father_by_mother']['id'] => $n['grand_father_by_mother'],
            $n['father_without_parents']['id'] => $n['father_without_parents'],
            $n['mother_with_father_and_son']['id'] => $n['mother_with_father_and_son'],
            $n['mothers_son']['id'] => $n['mothers_son'],
            $n['root']['id'] => $n['root'],
            //$n[''], $n[''],
        ];


        return $result;
    }

    protected function getNodes()
    {
        $nodes = [];

        $nodes['great_grand_father'] = [
            "id" => 1400,
            "parent_id" => 1000,
            'dead' => '0',
            "death_date" => null,
            "first_name" => "Vlad",
            "middle_name" => "_____________",
            "gender" => "1",
            "email" => "_____________",
            "root" => 0,
            "last_name" => "Chumak",
            "node_type" => "1",
            "b_date" => null,
            "image" => "http://kulbeli.app/images/male.jpg",
            "death" => [
                "id" => "1",
                "country" => "",
                "city" => "",
                "date" => null,
                "reason" => "",
                "bury_country" => "",
                "bury_city" => "",
                "cemetery" => "",
                "bury_date" => null
            ],
            "birth" => "__ ___ ____",
            "full_name" => "Vlad Chumak",
            "class" => "man",
            "name" => "Vlad",
            "father_id" => null,
            "mother_id" => null,
            "married" => 0
        ];

        $nodes['grand_father'] = [
            "id" => 1000,
            "parent_id" => 800,
            'dead' => '0',
            "death_date" => null,
            "first_name" => "Vasyl",
            "middle_name" => "_____________",
            "gender" => "1",
            "email" => "_____________",
            "root" => 0,
            "last_name" => "Chumak",
            "node_type" => "1",
            "b_date" => null,
            "image" => "http://kulbeli.app/images/male.jpg",
            "death" => [
                "id" => "1",
                "country" => "",
                "city" => "",
                "date" => null,
                "reason" => "",
                "bury_country" => "",
                "bury_city" => "",
                "cemetery" => "",
                "bury_date" => null
            ],
            "birth" => "__ ___ ____",
            "full_name" => "Vasyl Chumak",
            "class" => "man",
            "name" => "Vasyl",
            "father_id" => null,
            "mother_id" => null,
            "married" => 1
        ];

        $nodes['grand_mother'] = [
            "id" => 1100,
            "parent_id" => 800,
            'dead' => '0',
            "death_date" => null,
            "first_name" => "Elena",
            "middle_name" => "_____________",
            "gender" => "0",
            "email" => "_____________",
            "root" => 0,
            "last_name" => "Novozheeva",
            "node_type" => "1",
            "b_date" => null,
            "image" => "http://kulbeli.app/images/female.jpg",
            "death" => [
                "id" => "1100",
                "country" => "",
                "city" => "",
                "date" => null,
                "reason" => "",
                "bury_country" => "",
                "bury_city" => "",
                "cemetery" => "",
                "bury_date" => null
            ],
            "birth" => "__ ___ ____",
            "full_name" => "Elena Novozheeva",
            "class" => "woman",
            "name" => "Elena",
            "father_id" => null,
            "mother_id" => null,
            "married" => 1
        ];

        $nodes['father_with_marriage_and_children'] = [
            "id" => 800,
            "parent_id" => 1,
            'dead' => '0',
            "death_date" => null,
            "first_name" => "Alex",
            "middle_name" => "_____________",
            "gender" => "1",
            "email" => "_____________",
            "root" => 0,
            "last_name" => "Chumak",
            "node_type" => "2",
            "b_date" => null,
            "image" => "http://kulbeli.app/images/male.jpg",
            "death" => [
                "id" => "800",
                "country" => "",
                "city" => "",
                "date" => null,
                "reason" => "",
                "bury_country" => "",
                "bury_city" => "",
                "cemetery" => "",
                "bury_date" => null
            ],
            "birth" => "__ ___ ____",
            "full_name" => "Alex Chumak",
            "class" => "man",
            "name" => "Alex",
            "father_id" => 1000,
            "mother_id" => 1100,
            "married" => 1
        ];

        $nodes['father_without_parents'] = [
            "id" => 800,
            "parent_id" => 1,
            'dead' => '0',
            "death_date" => null,
            "first_name" => "Alex",
            "middle_name" => "_____________",
            "gender" => "1",
            "email" => "_____________",
            "root" => 0,
            "last_name" => "Chumak",
            "node_type" => "2",
            "b_date" => null,
            "image" => "http://kulbeli.app/images/male.jpg",
            "death" => [
                "id" => "800",
                "country" => "",
                "city" => "",
                "date" => null,
                "reason" => "",
                "bury_country" => "",
                "bury_city" => "",
                "cemetery" => "",
                "bury_date" => null
            ],
            "birth" => "__ ___ ____",
            "full_name" => "Alex Chumak",
            "class" => "man",
            "name" => "Alex",
            "father_id" => null,
            "mother_id" => null,
            "married" => 1
        ];

        $nodes['father_without_marriage'] = [
            "id" => 800,
            "parent_id" => 1,
            'dead' => '0',
            "death_date" => null,
            "first_name" => "Alex",
            "middle_name" => "_____________",
            "gender" => "1",
            "email" => "_____________",
            "root" => 0,
            "last_name" => "Chumak",
            "node_type" => "2",
            "b_date" => null,
            "image" => "http://kulbeli.app/images/male.jpg",
            "death" => [
                "id" => "800",
                "country" => "",
                "city" => "",
                "date" => null,
                "reason" => "",
                "bury_country" => "",
                "bury_city" => "",
                "cemetery" => "",
                "bury_date" => null
            ],
            "birth" => "__ ___ ____",
            "full_name" => "Alex Chumak",
            "class" => "man",
            "name" => "Alex",
            "father_id" => 1000,
            "mother_id" => 1100,
            "married" => 0
        ];

        $nodes['grand_father_by_mother'] = [
            "id" => 1500,
            "parent_id" => 900,
            'dead' => '0',
            "death_date" => null,
            "first_name" => "Kazymyr",
            "middle_name" => "_____________",
            "gender" => "1",
            "email" => "_____________",
            "root" => 0,
            "last_name" => "Shnider",
            "node_type" => "1",
            "b_date" => null,
            "image" => "http://kulbeli.app/images/male.jpg",
            "death" => [
                "id" => "1",
                "country" => "",
                "city" => "",
                "date" => null,
                "reason" => "",
                "bury_country" => "",
                "bury_city" => "",
                "cemetery" => "",
                "bury_date" => null
            ],
            "birth" => "__ ___ ____",
            "full_name" => "Kazymyr Shnider",
            "class" => "man",
            "name" => "Kazymyr",
            "father_id" => null,
            "mother_id" => null,
            "married" => 0
        ];

        $nodes['mother'] = [
            'id' => 900,
            'parent_id' => 800,
            'dead' => '0',
            'death_date' => NULL,
            'first_name' => 'Iryna',
            'middle_name' => '_____________',
            'gender' => '1',
            'email' => '_____________',
            "root" => 0,
            'last_name' => 'Chumak',
            'node_type' => '2',
            'b_date' => NULL,
            'image' => 'http://kulbeli.app/images/male.jpg',
            'death' =>
                [
                    'id' => '900',
                    'country' => '',
                    'city' => '',
                    'date' => NULL,
                    'reason' => '',
                    'bury_country' => '',
                    'bury_city' => '',
                    'cemetery' => '',
                    'bury_date' => NULL,
                ],
            'birth' => '__ ___ ____',
            'full_name' => 'Iryna Chumak',
            'class' => 'man',
            'name' => 'Iryna',
            "father_id" => null,
            "mother_id" => null,
            "married" => 1
        ];

        $nodes['mother_with_father'] = [
            'id' => 900,
            'parent_id' => 800,
            'dead' => '0',
            'death_date' => NULL,
            'first_name' => 'Iryna',
            'middle_name' => '_____________',
            'gender' => '1',
            'email' => '_____________',
            "root" => 0,
            'last_name' => 'Chumak',
            'node_type' => '2',
            'b_date' => NULL,
            'image' => 'http://kulbeli.app/images/male.jpg',
            'death' =>
                [
                    'id' => '900',
                    'country' => '',
                    'city' => '',
                    'date' => NULL,
                    'reason' => '',
                    'bury_country' => '',
                    'bury_city' => '',
                    'cemetery' => '',
                    'bury_date' => NULL,
                ],
            'birth' => '__ ___ ____',
            'full_name' => 'Iryna Chumak',
            'class' => 'man',
            'name' => 'Iryna',
            "father_id" => 1500,
            "mother_id" => null,
            "married" => 1
        ];

        $nodes['mother_with_father_and_son'] = [
            'id' => 900,
            'parent_id' => 800,
            'dead' => '0',
            'death_date' => NULL,
            'first_name' => 'Iryna',
            'middle_name' => '_____________',
            'gender' => '0',
            'email' => '_____________',
            "root" => 0,
            'last_name' => 'Chumak',
            'node_type' => '2',
            'b_date' => NULL,
            'image' => 'http://kulbeli.app/images/male.jpg',
            'death' =>
                [
                    'id' => '900',
                    'country' => '',
                    'city' => '',
                    'date' => NULL,
                    'reason' => '',
                    'bury_country' => '',
                    'bury_city' => '',
                    'cemetery' => '',
                    'bury_date' => NULL,
                ],
            'birth' => '__ ___ ____',
            'full_name' => 'Iryna Chumak',
            'class' => 'man',
            'name' => 'Iryna',
            "father_id" => 1500,
            "mother_id" => null,
            "married" => 1
        ];

        $nodes['mothers_son'] = [
            'id' => 1400,
            'parent_id' => 900,
            'dead' => '0',
            'death_date' => NULL,
            'first_name' => 'John',
            'middle_name' => '_____________',
            'gender' => '1',
            'email' => '',
            "root" => 0,
            'last_name' => 'Chumak',
            'node_type' => '6',
            'b_date' => null,
            'image' => 'http://kulbeli.app/images/male.jpg',
            'death' => NULL,
            'birth' => null,
            'full_name' => 'John Chumak',
            'class' => 'man',
            'name' => 'John',
            "father_id" => null,
            "mother_id" => 900,
            "married" => 0
        ];

        $nodes['root'] = [
            'id' => 1,
            'parent_id' => NULL,
            'dead' => '0',
            'death_date' => NULL,
            'first_name' => 'Volodymyr',
            'middle_name' => '_____________',
            'gender' => '1',
            'email' => 'chumak@example.com',
            "root" => 1,
            'last_name' => 'Chumak',
            'node_type' => '0',
            'b_date' => '1984-03-09',
            'image' => 'http://kulbeli.app/images/male.jpg',
            'death' =>
                [
                    'id' => '900',
                    'country' => '',
                    'city' => '',
                    'date' => NULL,
                    'reason' => '',
                    'bury_country' => '',
                    'bury_city' => '',
                    'cemetery' => '',
                    'bury_date' => NULL,
                ],
            'birth' => '09 Mar 1984',
            'full_name' => 'Volodymyr Chumak',
            'class' => 'man',
            'name' => 'Volodymyr',
            "father_id" => 800,
            "mother_id" => 900,
            "married" => 0
        ];

        $nodes['root_with_marriage_and_children'] = [
            'id' => 1,
            'parent_id' => NULL,
            'dead' => '0',
            'death_date' => NULL,
            'first_name' => 'Volodymyr',
            'middle_name' => '_____________',
            'gender' => '1',
            "root" => 1,
            'email' => 'chumak@example.com',
            'last_name' => 'Chumak',
            'node_type' => '0',
            'b_date' => '1984-03-09',
            'image' => 'http://kulbeli.app/images/male.jpg',
            'death' =>
                [
                    'id' => '900',
                    'country' => '',
                    'city' => '',
                    'date' => NULL,
                    'reason' => '',
                    'bury_country' => '',
                    'bury_city' => '',
                    'cemetery' => '',
                    'bury_date' => NULL,
                ],
            'birth' => '09 Mar 1984',
            'full_name' => 'Volodymyr Chumak',
            'class' => 'man',
            'name' => 'Volodymyr',
            "father_id" => 800,
            "mother_id" => 900,
            "married" => 1
        ];

        $nodes['wife'] = [
            'id' => 1200,
            'parent_id' => 1,
            'dead' => '0',
            'death_date' => NULL,
            'first_name' => 'Maryna',
            'middle_name' => '_____________',
            'gender' => '0',
            'email' => '',
            "root" => 0,
            'last_name' => 'Chumak',
            'node_type' => '7',
            'b_date' => '1992-04-16',
            'image' => 'http://kulbeli.app/images/male.jpg',
            'death' => NULL,
            'birth' => '16 Apr 1984',
            'full_name' => 'Maryna Chumak',
            'class' => 'woman',
            'name' => 'Maryna',
            "father_id" => null,
            "mother_id" => null,
            "married" => 1
        ];

        $nodes['son'] = [
            'id' => 1300,
            'parent_id' => 1,
            'dead' => '0',
            'death_date' => NULL,
            'first_name' => 'Pavlo',
            'middle_name' => '_____________',
            'gender' => '1',
            'email' => '',
            "root" => 0,
            'last_name' => 'Chumak',
            'node_type' => '7',
            'b_date' => '1992-04-16',
            'image' => 'http://kulbeli.app/images/male.jpg',
            'death' => NULL,
            'birth' => '07 July 2017',
            'full_name' => 'Pavlo Chumak',
            'class' => 'man',
            'name' => 'Pavlo',
            "father_id" => 1,
            "mother_id" => 1200,
            "married" => 0
        ];

        return $nodes;
    }
}
