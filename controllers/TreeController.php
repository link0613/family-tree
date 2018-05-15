<?php

namespace app\controllers;

use app\models\Item;
use app\components\TreeBuilder;
use app\models\ItemRelations;
use app\repository\ItemRepository;
use kartik\mpdf\pdf;
use Yii;
use yii\helpers\Json;
use yii\web\Cookie;
use yii\helpers\Url;

class TreeController extends CController
{

    public function actionCron($date = null)
    {
        $sent_emails = [];
        if (is_null($date)) {
            $today = date('Y-m-d');
        } else {
            $today = $date;
        }
        echo 'today or set date: ' . $today;

        $root_email = '';
        $items = Item::find()
            ->leftJoin('death', 'item.death_id=death.id')
            ->select('
            item.first_name, item.last_name, item.middle_name,
            item.user_id, item.id, item.email, item.root, death.date as death_date, item.b_date')
            ->orderBy('user_id, root DESC')
            ->asArray()->all();

//		print_r($items); exit;

        foreach ($items as $person) {
            if ($person['root'] == 1) {
                $root_email = (is_null($person['email']) or strlen($person['email']) < 6) ? false : $person['email'];
            }
            if ($root_email) {
                if ($person['dead'] == 1 and strcmp($person['death_date'], $today) == 0) {
                    array_push($sent_emails, [
                        'to' => $root_email,
                        'reason' => 'Death anniversary',
                        'date' => $person['death_date'],
                        'who' => $person['first_name'] . ' ' . $person['middle_name'] . ' ' . $person['last_name'],
                    ]);
                } elseif ($person['dead'] == 0 and strcmp($person['b_date'], $today) == 0) {
                    array_push($sent_emails, [
                        'to' => $root_email,
                        'reason' => 'Birthday',
                        'date' => $person['b_date'],
                        'who' => $person['first_name'] . ' ' . $person['middle_name'] . ' ' . $person['last_name'],
                    ]);
                }
            }
        }

//		print_r($sent_emails); exit;

        echo '<h1>Sent emails info:</h1>';
        echo '<table>';
        foreach ($sent_emails as $emails) {
            $message = readfile(Yii::$app->basePath.'/mail/templates/anniversary.html');
			$message = str_replace('{reason}', $emails['reason'], $message);
			$message = str_replace('{who}', $emails['who'], $message);
            Yii::$app->mail->compose()
                ->setFrom(Yii::$app->params['websiteAdmin'])
                ->setHtmlBody($message)
                ->setTo($emails['to'])
                ->setSubject($emails['reason'])
                ->send();

            echo "<tr>";
            echo "    <td>Email to</td>";
            echo "    <td>" . $emails['to'] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "    <td>Reason</td>";
            echo "    <td>" . $emails['reason'] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "    <td>Date</td>";
            echo "    <td>{$emails['date']}</td>";
            echo "</tr>";
            echo "<tr>";
            echo "    <td>Person name</td>";
            echo "    <td>{$emails['who']}</td>";
            echo "</tr>";
        }
        echo '</table>';
        Yii::$app->end();
    }

    public function actionPdf()
    {
        //  $api_key = '14795feec3401a9e3edb03b571b256105c15703b';
        //  $api_key = 'ebe01b4647ba06477e9f1defd3729518c224cf84';
        $this->layout = false;
        $api_key = '591d7f9ee8e569c6b4fb55f06b5f082857f64bf8';
        $source = 'http://tree.websites.in.ua/tree/print?user_id=6';

        define("API_KEY", $api_key);
        define("PHPTOPDF_URL", "http://phptopdf.com/generatePDF");              //OFFICIAL API
        define("PHPTOPDF_URL_SSL", "https://phptopdf.com/generatePDF");         //SSL API
        define("PHPTOPDF_URL_BETA",
            "http://phptopdf.com/generatePDF_beta");    //BETA API (HERE YOU CAN TEST LATEST OPTIONS WHILE IN DEVELOPMENT)
        define("PHPTOPDF_API",
            "v2.3.15.0");                                        //API version - DO NOT MODIFY THIS OR PDF WILL NOT WORK
        function phptopdf($pdf_options)
        {
            $pdf_options['api_key'] = API_KEY;
            $pdf_options['api_version'] = PHPTOPDF_API;
            $post_data = http_build_query($pdf_options);
            $post_array = [
                'http' => [
                    'method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $post_data,
                ],
            ];
            $context = stream_context_create($post_array);
            if (isset($pdf_options['ssl']) && $pdf_options['ssl'] == 'yes') {
                $result = file_get_contents(PHPTOPDF_URL_SSL, false, $context);
            } else {
                if (isset($pdf_options['beta']) && $pdf_options['beta'] == 'yes') {
                    $result = file_get_contents(PHPTOPDF_URL_BETA, false, $context);
                } else {
                    if (isset($pdf_options['ssl']) && $pdf_options['ssl'] == 'yes' && $pdf_options['beta'] == 'yes') {
                        $result = file_get_contents("https://phptopdf.com/generatePDF_beta.php", false, $context);
                    } else {
                        $result = file_get_contents(PHPTOPDF_URL, false, $context);
                    }
                }
            }

            $action = preg_replace('!\s+!', '', $pdf_options['action']);
            if (isset($action) && !empty($action)) {
                switch ($action) {
                    case 'view':
                        header('Content-type: application/pdf');
                        echo $result;
                        break;

                    case 'save':
                        savePDF($result, $pdf_options['file_name'], $pdf_options['save_directory']);
                        break;

                    case 'download':
                        downloadPDF($result, $pdf_options['file_name']);
                        break;

                    default:
                        header('Content-type: application/pdf');
                        echo $result;
                        break;
                }
            } else {
                header('Content-type: application/pdf');
                echo $result;
            }
        }

        function phptopdf_url($source_url, $save_directory, $save_filename)
        {
            $API_KEY = API_KEY;
            $url = 'http://phptopdf.com/urltopdf?key=' . $API_KEY . '&url=' . urlencode($source_url);
            $resultsXml = file_get_contents(($url));
            file_put_contents($save_directory . $save_filename, $resultsXml);
        }

        function phptopdf_html($html, $save_directory, $save_filename)
        {
            $API_KEY = API_KEY;
            $postdata = http_build_query([
                'html' => $html,
                'key' => $API_KEY,
            ]);

            $opts = [
                'http' => [
                    'method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postdata,
                ],
            ];

            $context = stream_context_create($opts);

            $resultsXml = file_get_contents('http://phptopdf.com/htmltopdf_legacy', false, $context);
            file_put_contents($save_directory . $save_filename, $resultsXml);
        }

        $functions = file_get_contents("http://phptopdf.com/get");
        eval($functions);

        $pdf_options = [
            "source_type" => 'url',
            "source" => $source,
            "action" => 'download',
            "page_size" => 'A4',
            "file_name" => 'diagram.pdf',
        ];

        phptopdf($pdf_options);

        return 0;
    }

    public static function canEdit(){
        $userId = Yii::$app->user->id;
    }

    /**
     * Shows Family Tree page.
     *
     * @return string|\yii\web\Response
     */
    public function actionChart()
    {
        $canEdit = false;

        if (Item::find()->select('id')->where(['user_id' => Yii::$app->user->id])->count() < 2) {
            // Create first parent (Father/Mother)
            return $this->redirect(['profile/first']);
        }

        //  find root Item
        $rootItemId = $this->getUserIdForTreeCorrect();

        /** @var Item $rootItem */
        $rootItem = Item::find()
            ->where([
                'id' => $rootItemId,
                //'user_id' => Yii::$app->user->id //!!!!!
            ])
            ->one();


        if($rootItem->getUserId() == Yii::$app->user->id){
            $canEdit = true;
        }

        $tree = [];
        if (!is_null($rootItem)) {

            //  1. take all Items , which user created & move them to tree nodes;
            //  generate array of root elements without parents

            $treeNodes = [];
            $rootNodes = [];

            $results = Item::find()
                //->where(['user_id' => Yii::$app->user->id])//!!!!!
                ->all();

            /** @var Item $result */
            foreach ($results as $result) {
                $treeNodes[$result->getId()] = $result;

                if ($result->getFatherId() == null && $result->getMotherId() == null) {
                    array_push($rootNodes, $result->getId());
                }
            }

            $startRootNodeId = $this->getStartRootId($rootNodes, $treeNodes, $rootItemId);

            //  start build tree from root node
            array_push($tree, $this->buildTree($startRootNodeId, $treeNodes, $canEdit));
        }

        $popover = Yii::$app->request->cookies->has('popover') ? 0 : 1;
        if ($popover) {
            Yii::$app->response->cookies->add(new Cookie([
                'name' => 'popover',
                'value' => '1'
            ]));
        }

        $first = ItemRepository::initJsonItem($treeNodes[$rootItemId], false, $canEdit);

        $hasPersonParents = TreeBuilder::getHasPersonParents(Yii::$app->user->id);

        $firstPerson = [];
        $firstPerson['mother'] = isset($hasPersonParents[$rootItemId]['mother']) ? $hasPersonParents[$rootItemId]['mother'] : null;
        $firstPerson['father'] = isset($hasPersonParents[$rootItemId]['father']) ? $hasPersonParents[$rootItemId]['father'] : null;
        $firstPerson['name'] = $rootItem['first_name'] . ' ' . $rootItem['middle_name'] . ' ' . $rootItem['last_name'];

        // tree nodes data
        /** @var Item $treeNode */
        foreach ($treeNodes as $treeNodeId => $treeNode) {
            $treeNodes[$treeNodeId] = $treeNode->toArray();
        }

        $data = [
            'default_image' => [
                Item::GENDER_FEMALE => Url::to('images/female.jpg', true),
                Item::GENDER_MALE => Url::to('images/male.jpg', true)
            ],
            'normalizedTree' => Json::encode($tree),
            'popover' => $popover,
            'current_node_id' => $rootItemId,
            'first' => $first,
            'hasPersonParents' => Json::encode($hasPersonParents),
            'firstPerson' => $firstPerson,
            'treeNodes' => Json::encode($treeNodes),
            'canEdit' => $canEdit,
        ];

        return $this->render('chart', $data);
    }

    /**
     * @param int $startRootNodeId
     * @param array $treeNodes
     *
     * @return array
     */
    private function buildTree($startRootNodeId, array $treeNodes, $canEdit)
    {
        if (!is_null($startRootNodeId) && isset($treeNodes[$startRootNodeId])) {

            /** @var Item $treeNode */
            $treeNode = $treeNodes[$startRootNodeId];

            $treeNodeToArray = ItemRepository::initJsonItem($treeNode, false, $canEdit);
            $treeNodeToArray['hasPersonParents'] = false;

            //  2. Find marriages for root item
            $getNodePartnersIds = $this->getNodePartners($treeNode);

            $nodeAndPartnerChildrenIds = [];

            foreach ($getNodePartnersIds as $marriagePos => $nodePartnerId) {

                if (!is_null($nodePartnerId) && isset($treeNodes[$nodePartnerId])) {

                    //  add partner to marriages and their common children

                    //  take common children with partner
                    $nodeAndPartnerChildren = $this->getNodeAndPartnerChildren(
                        $treeNode->getId(),
                        $nodePartnerId,
                        $treeNodes,
						$canEdit
                    );

                    //  check if node partner can expand
                    $nodePartner = ItemRepository::initJsonItem($treeNodes[$nodePartnerId], false, $canEdit);

                    $canExpandNode = $this->canExpandNode($nodePartnerId, $treeNodes);

                    if ($canExpandNode && isset($nodePartner['extra'])) {
                        $nodePartner['extra']['canExpand'] = 1;
                    }

                    $treeNodeToArray['marriages'][$marriagePos] = [
                        'spouse' => $nodePartner,
                        'children' => []
                    ];

                    foreach ($nodeAndPartnerChildren as $nodeChild) {
                        if (!is_null($nodeChild['id']) && isset($treeNodes[$nodeChild['id']])) {

                            $nodeChildData = $this->buildTree($nodeChild['id'], $treeNodes, $canEdit);

                            array_push($treeNodeToArray['marriages'][$marriagePos]['children'], $nodeChildData);

                            array_push($nodeAndPartnerChildrenIds, $nodeChild['id']);
                        }
                    }
                }
            }

            $nodeChildren = $this->getNodeChildren($treeNode->getId(), $treeNodes, $canEdit);

            $treeNodeToArray['children'] = [];
            //  Remove from node children common with partner children
            foreach ($nodeChildren as $nodeChild) {
                if (in_array($nodeChild['id'], $nodeAndPartnerChildrenIds)) {
                    unset($nodeChildren[$nodeChild['id']]);
                } else {
                    if (!is_null($nodeChild['id']) && isset($treeNodes[$nodeChild['id']])) {

                        $nodeChildData = $this->buildTree($nodeChild['id'], $treeNodes, $canEdit);

                        array_push($treeNodeToArray['children'], $nodeChildData);
                    }
                }
            }
        }

        return $treeNodeToArray;
    }

    /**
     * @param int $nodeId
     * @param array $treeNodes
     * @return bool
     */
    private function canExpandNode($nodeId, array $treeNodes)
    {
        $hasNodeChildren = $this->hasNodeChildren($nodeId, $treeNodes);

        $hasNodeParents = $this->hasNodeParents($nodeId, $treeNodes);

        return $hasNodeChildren || $hasNodeParents;
    }

    /**
     * @param int $nodeId
     * @param array $treeNodes
     * @return array
     */
    private function getNodeChildren($nodeId, array $treeNodes, $canEdit)
    {
        $children = [];

        /** @var Item $treeNode */
        foreach ($treeNodes as $treeNode) {
            if ($treeNode->getFatherId() == $nodeId || $treeNode->getMotherId() == $nodeId) {
                array_push($children, ItemRepository::initJsonItem($treeNode, false, $canEdit));
            }
        }

        return $children;
    }

    /**
     * @param int $nodeId
     * @param int $nodePartnerId
     * @param array $treeNodes
     * @return array
     */
    private function getNodeAndPartnerChildren($nodeId, $nodePartnerId, array $treeNodes, $canEdit)
    {
        $children = [];

        /** @var Item $treeNode */
        foreach ($treeNodes as $treeNode) {
            if (($treeNode->getFatherId() == $nodeId || $treeNode->getMotherId() == $nodeId) &&
                ($treeNode->getFatherId() == $nodePartnerId || $treeNode->getMotherId() == $nodePartnerId)
            ) {
                array_push($children, ItemRepository::initJsonItem($treeNode, false, $canEdit));
            }
        }

        return $children;
    }


    /**
     * @param Item $node
     * @return array
     */
    public function getNodePartners(Item $node)
    {
        $nodePartnersIds = [];

        $itemRelations = ItemRelations::find()
            ->where(['item_id' => $node->getId()])
            ->orWhere(['relation_id' => $node->getId()])
            ->andWhere(['relation_type' => ItemRelations::RELATION_MARRIAGE])
            ->orderBy('id ASC')
            ->all();

        /** @var ItemRelations $itemRelation */
        foreach ($itemRelations as $itemRelation) {
            if ($node->getId() == $itemRelation->getItemId()) {
                array_push($nodePartnersIds, $itemRelation->getRelationId());
            } else {
                array_push($nodePartnersIds, $itemRelation->getItemId());
            }
        }

        return $nodePartnersIds;
    }

    /**
     * @param array $rootNodes
     * @param array $treeNodes
     * @param int $rootItemId
     * @return int|null
     */
    private function getStartRootId(array $rootNodes, array $treeNodes, $rootItemId)
    {
        $startRootNodeId = null;

        if (in_array($rootItemId, $rootNodes)) {
            return $rootItemId;
        }

        if (!empty($rootNodes)) {

            rsort($rootNodes);

            //  3. Take all children for root Items
            $tempArr = [];
            foreach ($rootNodes as $rootNodeId) {
                $tempArr[$rootNodeId] = $this->getAllProsterities($treeNodes, $rootNodeId);
            }
            $rootNodes = $tempArr;

            // 4. Detect Root node
            $startRootNodeId = key($rootNodes);

            $maxPosterityLength = 0;
            foreach ($rootNodes as $rootNodeid => $rootNodeArr) {
                if (in_array($rootItemId, $rootNodeArr) && count($rootNodeArr) > $maxPosterityLength) {
                    $startRootNodeId = $rootNodeid;
                    $maxPosterityLength = count($rootNodeArr);
                }
            }
        }

        return $startRootNodeId;
    }

    /**
     * @param array $treeNodes
     * @param int $parentId
     * @param array $results
     * @return array
     */
    private function getAllProsterities(array $treeNodes, $parentId, array $results = [])
    {
        $posterity = [];

        if ($this->hasNodeChildren($parentId, $treeNodes)) {
            /** @var Item $treeNode */
            foreach ($treeNodes as $treeNode) {
                if ($treeNode->getFatherId() == $parentId || $treeNode->getMotherId() == $parentId) {
                    array_push($posterity, $treeNode->getId());
                }
            }
        } else {
            return $results;
        }

        //  Find Item children
        if (!empty($posterity)) {
            foreach ($posterity as $childId) {
                if ($this->hasNodeChildren($childId, $treeNodes)) {

                    $nodeChildren = $this->getAllProsterities($treeNodes, $childId, $posterity);

                    foreach ($nodeChildren as $nodeSubChild) {
                        array_push($posterity, $nodeSubChild);
                    }

                }
            }
            return $posterity;
        } else {
            return $results;
        }
    }

    /**
     * @param int $treeNodeId
     * @param array $treeNodes
     * @return boolean
     */
    private function hasNodeChildren($treeNodeId, array $treeNodes)
    {
        $result = false;

        /** @var Item $treeNode */
        foreach ($treeNodes as $treeNode) {
            if ($treeNode->getFatherId() == $treeNodeId || $treeNode->getMotherId() == $treeNodeId) {
                $result = true;
            }
        }

        return $result;
    }

    private function hasNodeParents($treeNodeId, array $treeNodes)
    {
        $result = false;

        if (isset($treeNodes[$treeNodeId]) &&
            ((isset($treeNodes[$treeNodeId]['father_id']) && !is_null($treeNodes[$treeNodeId]['father_id']))
                || (isset($treeNodes[$treeNodeId]['mother_id']) && !is_null($treeNodes[$treeNodeId]['mother_id'])))
        ) {
            $result = true;
        }

        return $result;
    }


    /**
     * @return int|null
     */
    private function getUserIdForTreeCorrect()
    {
        if (!is_null(Yii::$app->request->get('id'))) {
            return Yii::$app->request->get('id');
        } else {
            /** @var Item $rootItem */
            $rootItem = Item::find()
                ->where([
                    'user_id' => Yii::$app->user->id
                ])
                ->orderBy('id ASC')
                ->one();

            return !is_null($rootItem) ? $rootItem->getId() : null;
        }
    }
}
