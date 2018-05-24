<?php

namespace app\controllers;

use app\components\Main;
use app\models\Death;
use app\models\Education;
use app\models\Gotra;
use app\models\Item;
use app\models\ItemRelations;
use app\models\ItemSearch;
use app\models\Location;
use app\models\UploadForm;
use app\models\User;
use app\repository\ItemRepository;
use Yii;
use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use app\models\ProfileContactForm;


class ProfileController extends CController
{
    /**
     * Index action
     *
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        $profile = Item::find()->select('id')->where([
            'user_id' => Yii::$app->user->id,
            'root' => 1
        ])->one();

        if(is_null($profile)){
            $user = User::findOne(Yii::$app->user->getId());
            if (!is_null($user)) {
                Yii::$app->user->logout();
                $user->delete();
            }
            return $this->redirect(['site/index']);
        }else{
            return $this->redirect(['view', 'id' => $profile->getId()]);
        }
    }

    /**
     * Shows page for adding parents to user
     *
     * @return mixed
     * @throws 404 when no education found for Item
     * @property \yii\web\AssetManager $assetManager
     */
    public function actionFirst()
    {
        /** @var Item $model */
        /** @var Item $rootItem */

        $rootItem = Item::find()->where([
            'user_id' => Yii::$app->user->id,
            'root' => 1
        ])->one();
        
        if (is_null($rootItem)) {
            throw new NotFoundHttpException('User no found. Please do re-login.');
        }

        return $this->render('first', [
            'rootItem' => $rootItem,
            'model' => new Item(['user_id' => Yii::$app->user->id, 'root' => 0]),
            'gotra' => new Gotra(),
            'gotras' => Gotra::find()->orderBy('name')->select('name')->asArray()->all()
        ]);
    }

    /**
     * @param int $id
     * @return string|\yii\web\Response
     */
    public function actionShow($id)
    {
        $profile = Item::find()->where(['id' => $id])->one();
        if (!is_null($profile)) {
            return $this->render('show', [
                'profile' => $profile,
            ]);
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Search for users
     *
     * @return mixed
     */
    public function actionSearch()
    {
		$myId = Yii::$app->main->getMyProfile();
		$item = $this->findModel($myId);

        if ($item->privacy) {
            Yii::$app->session->setFlash('error', 'Privacy should be public for searching.');

            return $this->redirect(['view', 'id' => $myId]);
        }

        $profilecontact = new ProfileContactForm();
		if (Yii::$app->request->post('ProfileContactForm')) {
            // Send contact form
            if ($profilecontact->load(Yii::$app->request->post()) ) {

                $profilecontact->sender = \Yii::$app->user->identity->email;

                $fromName = $item->first_name;
				if( $item->middle_name )
					$fromName .= " {$item->middle_name}";
				if( $item->last_name )
					$fromName .= " {$item->last_name}";
				if( $profilecontact->contact($fromName)) {
					Yii::$app->session->setFlash('success',
						'<p class="text-center text-success" style="font-size: 23px;">Message has been sent</p>');
				} else {
					Yii::$app->session->setFlash('success',
						'<p class="text-center text-danger" style="font-size: 23px;">Error. Message was not sent</p>');
				}

	            return $this->refresh();
			}
		}

        $searchModel = new ItemSearch();
        $dataProvider = new ActiveDataProvider([
            'query' => Item::find()->where(['user_id' => Yii::$app->user->getId()]),
        ]);
        if (Yii::$app->request->queryParams) {
			if( isset(Yii::$app->request->queryParams['ItemSearch']) )
				$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }

        return $this->render('search', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'profilecontact' => $profilecontact,
        ]);
    }

    /**
     * Changes password
     *
     * @return mixed
     */
    public function actionPassword()
    {
        $USER = User::findOne(Yii::$app->user->id);
        $user = new DynamicModel(['password']);
        $user
            ->addRule(['password'], 'required')
            ->addRule(['password'], 'string', ['max' => 100, 'min' => 6]);

        if (Yii::$app->request->post('DynamicModel')) {
            Yii::$app->session->setFlash('success', 'Password has been changed.');
            $user->load(Yii::$app->request->post());
            $USER->setPassword($user->password);
            if ($USER->save()) {
                return $this->refresh();
            }
        }

        return $this->render('password', [
            'user' => $user
        ]);
    }

    /**
     * Changes email
     *
     * @return mixed
     */
    public function actionEmail()
    {
        $user = User::findOne(Yii::$app->user->id);

        if (Yii::$app->request->post('User')) {
            $user->load(Yii::$app->request->post());
            if ($user->save()) {
                Yii::$app->session->setFlash('success', 'Email has been changed.');
                Yii::$app->user->logout();
                Yii::$app->user->login($user);

                return $this->refresh();
            }
        }

        return $this->render('email', [
            'user' => $user
        ]);
    }

    /**
     * Create new record if $id = null or view record if $id = number
     *
     * @param int|null $id Profile ID
     * @return mixed
     */
    public function actionView($id = null)
    {
        $data = Yii::$app->request->post();

        //TODO optimize here
        if(!empty($id)){
            $item = $this->findModel($id);
            if($item->getUserId() != Yii::$app->user->id){
                throw new CHttpException(403,'You are not authorized to per-form this action.');
                die();
            }
        }

        if (is_null($id)) {
            // Create new person
            $model = ItemRepository::create(Yii::$app->user->id, $data);
            if (!$model) {
                Yii::$app->end();
            }

            Yii::$app->main->setProfileId($model->id);
            Yii::$app->session->setFlash('success', 'Profile has been created.');
        } else {
            // Update item
            $model = $this->findModel($id);
            $model->load($data);
            $model->save();

            $profile_id = Yii::$app->main->getProfileId();
            if ($profile_id != $id) {
                Yii::$app->main->setProfileId($id);
            }
        }

        if ( Yii::$app->request->isPost ) {
            $component = new Main();
            $gotraId = $component->getGotraId(Yii::$app->request->post('Gotra')['name']);
            $model->setGotraId($gotraId);
            $model->save();
        }

        //  set death
        $death = Death::findOne(['item_id' => $model->getId()]);
        if (is_null($death)) {
            $death = new Death(['item_id' => $model->getId()]);
        }

        if ( Yii::$app->request->isPost ) {
            if (!is_null($model) && !is_null($death)
                && !empty($data['Death']) && !empty($data['Death']['city'])) {

                if(!empty($data['Death']['country'])) {
                    $death->setCountry($data['Death']['country']);
                }
                if(!empty($data['Death']['city'])) {
                    $death->setCity($data['Death']['city']);
                }
                if(!empty($data['Death']['date'])) {
                    $death->setDate($data['Death']['date']);
                }
                if(!empty($data['Death']['reason'])) {
                    $death->setReason($data['Death']['reason']);
                }
                if(!empty($data['Death']['bury_country'])) {
                    $death->setBuryCountry($data['Death']['bury_country']);
                }
                if(!empty($data['Death']['bury_city'])) {
                    $death->setBuryCity($data['Death']['bury_city']);
                }
                if(!empty($data['Death']['cemetery'])) {
                    $death->setCemetery($data['Death']['cemetery']);
                }
                if(!empty($data['Death']['bury_date'])) {
                    $death->setBuryDate($data['Death']['bury_date']);
                }
//            $death->setCountry($data['Death']['country'] ?? '');
//            $death->setCity($data['Death']['city'] ?? '');
//            $death->setDate($data['Death']['date'] ?? '');
//            $death->setReason($data['Death']['reason'] ?? '');
//            $death->setBuryCountry($data['Death']['bury_country'] ?? '');
//            $death->setBuryCity($data['Death']['bury_city'] ?? '');
//            $death->setCemetery($data['Death']['cemetery'] ?? '');
//            $death->setBuryDate($data['Death']['bury_date'] ?? '');

                if ($death->save()) {
                    $model->setDeathId($death->getId());
                    $model->save();
                }
            }

            //  returnToTree
            $returnToTreeId = Yii::$app->request->post('returnToTree', null);
            if (!is_null($returnToTreeId) && $returnToTreeId == true) {
                $this->refresh();
                return $this->redirect(['tree/chart', 'id' => $model->getId()]);
            }

            //  addNewNode
            $addNewNode = Yii::$app->request->post('addNewNode', null);
            if (!is_null($addNewNode) && $addNewNode == true) {
                $this->refresh();
                return $this->redirect(['profile/create']);
            }
        }

        $gotras = Gotra::find()->orderBy('name')->select('name')->asArray()->all();
        if (is_array($gotras) && !empty($gotras)) {
            $gotras = ArrayHelper::getColumn($gotras, 'name');
        }

        //echo '<pre>'; var_dump('####',$gotras); echo '</pre>';

        $gotra = Gotra::findOne($model->gotra_id);
        if(!empty($gotra)) {
            $gotra = $gotra;
        } else {
            $gotra = new Gotra();
        }

        //echo '<pre>'; var_dump('####',$gotra); echo '</pre>';

        if (Yii::$app->request->post('Item') && !is_null($id)) {
            Yii::$app->session->setFlash('success', 'Profile has been saved.');        

            return $this->refresh();
        }
        try {
            if (isset($data['stay_treeview'])) {
                return $this->render('view', [
                    'model' => $model,
                    'gotra' => $gotra,
                    'gotras' => $gotras,
                    'death' => $death
                ]);
            } else {
                $this->refresh();
                return $this->redirect(['tree/chart', 'id' => $model->getId()]);
            }
        } catch (Exception $e) {
            $this->refresh();
            return $this->redirect(['tree/chart', 'id' => $model->getId()]);
        }
    }

    /**
     * Create new node and set its relationships
     */
    public function actionCreate()
    {
        /** @var Item $resultItem */

        $results = Item::find()
            ->where(['user_id' => Yii::$app->user->getId()])
            ->orderBy('first_name ASC')
            ->all();

        $userItems = [];
        $userItemsData = [];
        foreach ($results as $resultItem) {
            $userItems[$resultItem->getId()] = $resultItem->getFullName();
            $userItemsData[$resultItem->getId()] = $resultItem->toArray();
        }

        // generate new Item model
        $model = new Item();

        // generate new Gotra model
        $gotra = new Gotra();

        // find all gotras
        $gotras = [];
        $results = Gotra::find()->orderBy('name')->select('name')->asArray()->all();
        if (is_array($results) && !empty($results)) {
            $gotras = ArrayHelper::getColumn($results, 'name');
        }

        $death = new Death();

        return $this->render('create', [
            'userItems' => $userItems,
            'userItemsData' => $userItemsData,
            'model' => $model,
            'gotra' => $gotra,
            'gotras' => $gotras,
            'death' => $death
        ]);
    }

    /**
     * Add education for Item
     *
     * @param int $id Item.id
     * @return mixed
     */
    public function actionEducation($id)
    {
        $educations = Education::find()->where(['item_id' => $id])->all();
        $education = new Education(['item_id' => $id]);
        if (Yii::$app->request->post('Education')) {
            $education->load(Yii::$app->request->post());
            $education->save();
            Yii::$app->session->setFlash('success', 'Education has been added.');

            return $this->refresh();
        }

        return $this->render('education', [
            'educations' => $educations,
            'education' => $education,
        ]);
    }

    /**
     * @param $image_name
     * @param $image_path
     * @param $dir
     * @param $w
     * @param $h
     * @return mixed
     */
    public function createImage($image_name, $image_path, $dir, $w, $h)
    {
        $quality = 9;
        //BackGround
        $img = \imagecreatetruecolor($w, $h);
        $bg = \imagecolorallocate($img, 255, 255, 255);
        \imagefilledrectangle($img, 0, 0, $w, $h, $bg);
        $image_path = file_exists($image_path) ? $image_path : '';

        //  get image type
        //  $src = @\ImageCreateFromJPEG($image_path) ? \ImageCreateFromJPEG($image_path) : \ImageCreateFromPNG($image_path);
        $imageData = explode('.', $image_name);
        $imageType = strtolower(end($imageData));
        if ($imageType == 'png') {
            $src = \ImageCreateFromPNG($image_path);
        } elseif ($imageType == 'jpg' || $imageType == 'jpeg') {
            $src = \ImageCreateFromJPEG($image_path);
        }

        $height = \imagesy($src);
        $width = \imagesx($src);
        $height_cut = 0;
        $width_cut = 0;
        if ($height > $width) {
            $index = $h / $height;
            $width_cut = ($w - $width * $index) / 2;
        } else {
            $index = $w / $width;
            $height_cut = ($h - $height * $index) / 2;
        }
        $scaled_img = \imagescale($src, round($width * $index), round($height * $index));
        \imagecopymerge($img, $scaled_img, $width_cut, $height_cut, 0, 0, round($width * $index),
            round($height * $index), 100);
        \imagepng($img, $dir . $image_name, $quality);
        \imagedestroy($img);
        \imagedestroy($src);

        return $image_name;
    }

    /**
     * @param null $email
     * @return string|\yii\web\Response
     */
    public function actionInvite($email = null)
    {
        $profiles = Yii::$app->main->getProfileList(0);
        $model = new DynamicModel(['email']);
        $model
            ->addRule(['email'], 'required')
            ->addRule(['email'], 'email')
            ->addRule(['email'], 'string', ['max' => 100, 'min' => 6]);

        if (!is_null($email) or Yii::$app->request->post('DynamicModel')) {
            $email = Yii::$app->request->post('DynamicModel')['email'];
            $item = Item::find()->where(['user_id' => Yii::$app->user->id, 'root' => 1])->one();
            if ($item) {
                $who = $item->first_name . ' ' . $item->last_name;
            } else {
                $who = '--------';
            }
            $url = $_SERVER['SERVER_NAME'];
           /** $message = readfile(Yii::$app->basePath.'/mail/templates/invitation.html');*/
$message = "
       <p> " . $who . " have invited you to join the <a href='" . $url . "'>Kulbeli</a>.</p>
<p>Creating the tree is very simple as it begins from you followed by other members in the right order. The members and then followed by their siblings and so on. Kulbeli is a hierarchy which shows all the family members together and how they are associated with one another.</p>

<p>Have questions? Get in touch with Kulbeli via Facebook or Twitter, or email our support team on info@kulbeli.com.</p>

<p>Feedback</p>

<p>We’re working hard to make Kulbeli even more wonderful. Please give us your feedback by emailing us (info@kulbeli.com)</p>

<p>Thank you</p>

<p>Team Kulbeli</p>
        ";
			$message = str_replace('{who}', $who, $message);
			$message = str_replace('{url}', $url, $message);
            $a = Yii::$app->mail->compose()
                ->setFrom(Yii::$app->params['infoEmail'])
                ->setHtmlBody($message) 

                ->setTo($email)
                ->setSubject('Invitation to create your Kulbeli')
                ->send();
            Yii::$app->session->setFlash('success', 'Invitation has been sent.');

            return $this->refresh();
        }

        return $this->render('invite', [
            'model' => $model,
            'profiles' => $profiles
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionImage($id)
    {
        $upload = new UploadForm();
        $profile = Item::find()->where(['id' => $id])->one();
        if (!is_null($profile) && Yii::$app->request->post('UploadForm') && Yii::$app->request->isPost) {
            $upload->image = UploadedFile::getInstance($upload, 'image');
            if ($image_name = $upload->upload()) {
                $old_img = $profile->image;
                $image_path = Url::to('images/profile' . '/' . $image_name);
                $profile->image = $this->createImage(
                    $image_name,
                    $image_path,
                    Url::to('images/profile') . '/',
                    300,
                    400
                );
                $profile->save();
                Yii::$app->session->setFlash('success', 'Image has been uploaded.');
                @unlink(Url::to('images/profile' . '/' . $old_img));

                return $this->refresh();
            }
        }

        return $this->render('image', [
            'profile' => $profile,
            'upload' => $upload
        ]);
    }

    /**
     * Add education for Item
     *
     * @param int $id Item.id
     * @throws 404 when no education found for Item
     * @return mixed
     */
    public function actionEducationedit($id)
    {
        $education = Education::find()
            ->joinWith('item')
            ->select('education.*')
            ->where('education.id=:id and item.user_id=:user_id', [':user_id' => Yii::$app->user->id, ':id' => $id])
            ->one();
        if (is_null($education)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (Yii::$app->request->post('Education')) {
            $education->load(Yii::$app->request->post());
            if ($education->save()) {
                Yii::$app->session->setFlash('success', 'Education has been saved.');

                return $this->redirect(['education', 'id' => $education->item_id]);
            }
        }

        return $this->render('education_edit', [
            'education' => $education
        ]);
    }

    /**
     * Add contact for Item
     *
     * @param int $id Item.id
     * @return mixed
     */
    public function actionContact($id)
    {
        $contacts = Location::find()->where(['item_id' => $id])->all();
        $contact = new Location(['item_id' => $id]);
        if (Yii::$app->request->post('Location')) {
            $contact->load(Yii::$app->request->post());
            $contact->save();
            Yii::$app->session->setFlash('success', 'Contact has been added.');

            return $this->refresh();
        }

        return $this->render('contact', [
            'contacts' => $contacts,
            'contact' => $contact
        ]);
    }

    /**
     * Set profile_id to session
     *
     * @throws 404 when request is not POST
     * @return mixed
     */
    public function actionSet()
    {
        if (Yii::$app->request->post('profile_id')) {
            Yii::$app->main->setProfileId(Yii::$app->request->post('profile_id'));

            return $this->redirect(['view', 'id' => Yii::$app->request->post('profile_id')]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Show all user`s Item
     *
     * @return mixed
     */
    public function actionFamily()
    {
        $searchModel = new ItemSearch();

        $dataProvider = new ActiveDataProvider([
            'query' => Item::find()->where(['user_id' => Yii::$app->user->getId()]),
        ]);

        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('family', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Add contact for Item
     *
     * @param int $id Item.id
     * @throws 404 when no contact found for Item
     * @return mixed
     */
    public function actionContactedit($id)
    {
        $contacts = Location::find()
            ->joinWith('item')
            ->select('location.*')
            ->where('location.id=:id and item.user_id=:user_id', [':user_id' => Yii::$app->user->id, ':id' => $id])
            ->one();

        if (is_null($contacts)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (Yii::$app->request->post('Location')) {
            $contacts->load(Yii::$app->request->post());
            if ($contacts->save()) {
                Yii::$app->session->setFlash('success', 'Location has been saved.');

                return $this->redirect(['contact', 'id' => $contacts->item_id]);
            }
        }

        return $this->render('contact_edit', [
            'contact' => $contacts
        ]);
    }

    /**
     * Remove current user's account
     */
    public function actionRemove()
    {
        $userId = Yii::$app->user->id;

        Yii::$app->user->logout();

        $user = User::findOne($userId);
        if (!is_null($user)) {
            $user->delete();
        }

        return $this->redirect(['site/index']);
    }

    /**
     * Deletes Item
     *
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $profile = Item::find()
            ->where([
                'id' => $id,
                'user_id' => Yii::$app->user->id
            ])
            ->one();

        if ($profile) {
            // delete Item occurrences as parent (mother/or father)

            $profileChildren = Item::find()
                ->where([
                    'user_id' => Yii::$app->user->id
                ])
                ->andWhere([
                    'father_id' => $profile->getId()
                ])
                ->orWhere([
                    'mother_id' => $profile->getId()
                ])
                ->all();

            foreach ($profileChildren as $profileChild) {
                $profileChild->setFatherId(null);
                $profileChild->save();
            }

            //  delete Item occurrences from marriages
            $itemRelations = ItemRelations::find()
                ->where([
                    'item_id' => $profile->getId()
                ])
                ->orWhere([
                    'relation_id' => $profile->getId()
                ])
                ->andWhere([
                    'relation_type' => ItemRelations::RELATION_MARRIAGE
                ])
                ->all();

            /** @var ItemRelations $itemRelation */
            foreach ($itemRelations as $itemRelation) {
                $itemRelation->delete();
            }

            // delete death data
            $death = Death::find(['item_id' => $profile->getId()])->one();
            if (!is_null($death)) {
                $death->delete();
            }

            if ($profile->root == 1) {
                Yii::$app->user->logout();
            }

            $profile->delete();
        }

        return $this->redirect(['family']);
    }

    /**
     * @param $id
     * @return Item
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Returns spouses for the passes item id.
     *
     * @param int $id
     * @return string
     */
    public function actionSpouses($id)
    {
        return Json::encode(ItemRepository::getSpouses(Yii::$app->user->id, $id));
    }

    /**
     * Returns marriages for Item
     *
     * @return string
     */
    public function actionMarriages()
    {
        /** @var ItemRelations $itemRelation */

        $spouses = [];

        $request = Yii::$app->request;

        $userId = $request->get('id', null);

        $allItemRelations = [];
        if (!is_null($userId)) {
            $allItemRelations = ItemRelations::find()
                ->where([
                    'item_id' => $userId
                ])
                ->orWhere([
                    'relation_id' => $userId
                ])
                ->andWhere([
                    'relation_type' => ItemRelations::RELATION_MARRIAGE
                ])
                ->orderBy('id ASC')
                ->all();
        }

        $allItemRelationsIds = [];
        foreach ($allItemRelations as $itemRelation) {
            if ($itemRelation->getItemId() == $userId) {
                array_push($allItemRelationsIds, $itemRelation->getRelationId());
            } elseif ($itemRelation->getRelationId() == $userId) {
                array_push($allItemRelationsIds, $itemRelation->getItemId());
            }
        }

        foreach ($allItemRelationsIds as $itemRelationsId) {
            $item = Item::findOne($itemRelationsId);
            if (!is_null($item)) {
                $spouses[] = [
                    $item->getId(),
                    $item->getFullName()
                ];
            }
        }

        return Json::encode($spouses);
    }

    /**
     * Returns marriages for Item
     *
     * @return string
     */
    public function actionChildren()
    {
        /** @var Item $parentItem */
        /** @var Item $resultItem */

        $children = [];

        $request = Yii::$app->request;

        $userId = $request->get('id', null);

        $parentItem = Item::findOne($userId);

        if (!is_null($userId) && !is_null($parentItem)) {

            if ($parentItem->getGender() == Item::GENDER_MALE) {
                $results = Item::find()
                    ->where([
                        'father_id' => $parentItem->getId(),
                        'mother_id' => null
                    ])
                    ->all();
            } elseif ($parentItem->getGender() == Item::GENDER_FEMALE) {
                $results = Item::find()
                    ->where([
                        'mother_id' => $parentItem->getId(),
                        'father_id' => null
                    ])
                    ->all();
            }

            foreach ($results as $resultItem) {
                array_push($children, [
                    'id' => $resultItem->getId(),
                    'fullName' => $resultItem->getFullName()
                ]);
            }
        }

        return Json::encode($children);
    }

    function actionPrivacy($privacy) {
        $privacy = (int)(bool)$privacy;
        Item::updateAll(['privacy' => $privacy], "user_id=".Yii::$app->user->id);
        $title = $privacy ? 'Private' : 'Public';
        Yii::$app->session->setFlash('success', "Privacy changed to '$title'");
        $this->goHome();
    }
}
