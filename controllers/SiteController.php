<?php

namespace app\controllers;

use app\components\Main;
use app\models\ContactForm;
use app\models\Info;
use app\models\Item;
use app\models\LoginForm;
use app\models\Researcher;
use app\models\SignupForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\TrAncestor;
use app\models\TrAncestorDetails;
use app\models\TrAncestorMigration;
use app\models\TrApplicant;
use app\models\TrUploadForm;
use Yii;
use yii\base\DynamicModel;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class SiteController extends CController
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
            ],
        ];
    }

    public function actionLogin()
    {
        return $this->redirect(['index']);
    }

    public function actionTermsupdate()
    {
        $info = Info::findOne(['id' => 1]);
        if (Yii::$app->request->post()) {
            $info->load(Yii::$app->request->post());
            if ($info->save()) {
                return $this->redirect('terms');
            }
        }
        return $this->render('termsupdate', ['info' => $info]);
    }

    public function actionTerms()
    {
        $info = Info::findOne(['id' => 1]);
        return $this->render('terms', ['info' => $info]);
    }


    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['profile/index']);
        }
        $contact = new ContactForm();
        if (Yii::$app->request->post('ContactForm')) {
            if ($contact->load(Yii::$app->request->post()) and $contact->contact(Yii::$app->params['infoEmail'])) {
                Yii::$app->session->setFlash('success',
                    '<p class="text-center text-success" style="font-size: 23px;">Message has been sent</p>');
            } else {
                Yii::$app->session->setFlash('success',
                    '<p class="text-center text-danger" style="font-size: 23px;">Error.</p>');
            }

            return $this->refresh();
        }
        $login = new LoginForm();
        $signup = new SignupForm();
        $component = new Main();
        $profile = new DynamicModel(['first_name', 'last_name', 'gender', 'gotra', 'b_date']);
        $profile
            ->addRule(['first_name', 'last_name', 'b_date', 'gender'], 'required')
            ->addRule(['first_name', 'last_name', 'b_date'], 'string', ['max' => 100, 'min' => 2]);

        if (Yii::$app->request->isAjax && $login->load(Yii::$app->request->post())) {
	        $aErrors = ActiveForm::validate($login);
            if (empty($aErrors)) {
                $login->login();
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $aErrors;
        } elseif (Yii::$app->request->isPost) {
            $signup->load(Yii::$app->request->post());
            if ($user = $signup->signup()) {
	            //first time allow to login without verification
	            //next time he can't login if email not confirmed
                Yii::$app->user->login($user);
                $profile->load(Yii::$app->request->post());
				if( $profile->attributes['gotra'] ) {
					$item = new Item([
						'root' => 1,
						'email' => $user->email,
						'user_id' => $user->id,
						'first_name' => $profile->attributes['first_name'],
						'last_name' => $profile->attributes['last_name'],
						'gender' => (int)$profile->attributes['gender'],
						'gotra_id' => $component->getGotraId($profile->attributes['gotra']),
						'b_date' => $profile->attributes['b_date']
					]);
	                $item->save();
				} else {
					$item = new Item([
						'root' => 1,
						'email' => $user->email,
						'user_id' => $user->id,
						'first_name' => $profile->attributes['first_name'],
						'last_name' => $profile->attributes['last_name'],
						'gender' => (int)$profile->attributes['gender'],
						'b_date' => $profile->attributes['b_date']
					]);
                    $item->save();
				}

				/*$message = readfile(Yii::$app->basePath.'/mail/templates/registration_welcome.html');*/
                Yii::$app->mail->compose()
                    ->setFrom(Yii::$app->params['websiteAdmin'])
                  /*  ->setHtmlBody( $message )*/
->setTextBody('
Pranam !!,

We just wanted to send you a quick note to say welcome to Kulbeli and thank you for signing up!

What next?

Here are a few steps you can take to get started and see how Kulbeli works... 

1.Add family members to your family tree.
2.Add Family members photos, birthdays and death anniversary, Kulbeli will notify such events to you.
3.Send invitation to your family members for creating an extended family tree.
4.Tell your friends about Kulbeli.

Feedback

We’re working hard to make Kulbeli even more wonderful. Please give us your feedback by emailing us (info@kulbeli.com)

Thank you
Team Kulbeli

')
                    ->setTo($user->email)
                    ->setSubject('Welcome to Kulbeli!')
                    ->send();
                return $this->redirect(['profile/first']);
            }
        }
        return $this->render('index', [
            'login' => $login,
            'contact' => $contact,
            'signup' => $signup,
            'profile' => $profile
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['/']);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionForgot()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionReset($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model
        ]);
    }

    /**
     * FAQ.
     *
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionFaq()
    {
		$this->layout='profile';
        return $this->render('faq');
    }

    function actionTracingRoots() {
        $this->layout='profile';

        $trApplicant = new TrApplicant();
	    if ( !Yii::$app->user->isGuest ) {
		    $trApplicant->contact_email = Yii::$app->user->identity->email;
	    }
        $trAncestor = new TrAncestor(['scenario'=>TrAncestor::SCENARIO_CLIENT_CREATE]);
        $trAncestorDetails = new TrAncestorDetails(['scenario'=>TrAncestorDetails::SCENARIO_CLIENT_CREATE]);
	    $trAncestorDetails->family_members = TrAncestorDetails::defaultFamilyMembers();
	    $trAncestorMigration = new TrAncestorMigration(['scenario'=>TrAncestorMigration::SCENARIO_CLIENT_CREATE]);
	    $trUploadForm = new TrUploadForm;

	    $trApplicant->populateRelation('trAncestors', [$trAncestor]);
	    $trAncestor->populateRelation('applicant', $trApplicant);
	    $trAncestor->populateRelation('trAncestorDetails', $trAncestorDetails);
	    $trAncestor->populateRelation('trAncestorMigration', $trAncestorMigration);
	    $trAncestorDetails->populateRelation('trAncestor', $trAncestor);
	    $trAncestorMigration->populateRelation('trAncestor', $trAncestor);

	    if ( Yii::$app->request->isPost ) {
		    $post = Yii::$app->request->post();

		    $trApplicant->load($post);
		    $trAncestor->load($post);
		    $trAncestorDetails->load($post);
		    $trAncestorMigration->load($post);

		    $trApplicant->validate();
		    $trAncestor->validate();
		    $trAncestorDetails->validate();
		    $trAncestorMigration->validate();

		    if ( $trApplicant->hasErrors() || $trAncestor->hasErrors() || $trAncestorDetails->hasErrors() || $trAncestorMigration->hasErrors() ) {
			    // if some errors found - do nothing. View will rendered with errors messages
			    Yii::$app->session->addFlash('danger','Some errors found - Fix them and resubmit form.');
		    } else { //all valid
			    $trApplicant->save(false);
			    $trAncestor->applicant_id = $trApplicant->id;
			    $trAncestor->save(false);
			    if ( $trAncestorDetails->family_members == TrAncestorDetails::defaultFamilyMembers() ) {
				    $trAncestorDetails->family_members = null;
			    }
			    $trAncestorDetails->id = $trAncestor->id;
			    $trAncestorDetails->save(false);
			    $trAncestorMigration->id = $trAncestor->id;
			    $trAncestorMigration->save(false);

			    $trUploadForm->files = UploadedFile::getInstances($trUploadForm, 'files');
			    $trUploadForm->upload($trApplicant->id, $trAncestor->id);

			    /** @var \app\components\MailHelper $mh */
			    $mh = Yii::$app->mailHelper;
			    $recordInfo = $mh->renderApplicationNumber($trApplicant->getUid());
			    $mh->sendConfirmation($trApplicant->contact_email, $trApplicant, $recordInfo);

			    Yii::$app->session->addFlash('success', "Success! Mail with verification link was sent to '<a href='mailto:$trApplicant->contact_email'>$trApplicant->contact_email</a>'. <p>Your application number is '<b>{$trApplicant->getUid()}</b>'. We Will get back to you.</p>");

			    $body = "<p>Application number is '<b>{$trApplicant->getUid()}</b>'.</p>";
			    $body .= "<p>Data: </p><br><pre>".print_r($trApplicant->humanReadableInfo(), true)."</pre>";
			    $mh->sendToAdmin("New Researcher #{$trApplicant->getUid()}", $body);

			    return $this->goHome();
		    }
	    }

        return $this->render('tracingRoots', [
	        'trApplicant'         => $trApplicant,
	        'trAncestor'          => $trAncestor,
	        'trAncestorDetails'   => $trAncestorDetails,
	        'trAncestorMigration' => $trAncestorMigration,
	        'trUploadForm'        => $trUploadForm,
        ]);
    }

	function actionConfirmEmail($email, $hash) {
		/** @var \app\components\MailHelper $mh */
		$mh = Yii::$app->mailHelper;
		$model = $mh->confirm($email, $hash);

		if (!$model) {
			Yii::$app->session->setFlash('danger', "Email '<b>$email</b>' does not confirmed.");
		} else {
			$model->emailConfirmed($email);
			Yii::$app->session->setFlash('success', "Email '<b>$email</b>' confirmed successfully!");
		}

		$this->goHome();
	}

	function actionBecomeResearcher() {
		$this->layout='profile';

		$researcher = new Researcher;
		$researcher->loadDefaultValues();
		if ( !Yii::$app->user->isGuest ) {
			$researcher->email = Yii::$app->user->identity->email;
		}

		if ( $researcher->load(Yii::$app->request->post()) ) {
			if ($researcher->save()) {
				/** @var \app\components\MailHelper $mh */
				$mh = Yii::$app->mailHelper;
				$recordInfo = $mh->renderApplicationNumber($researcher->getUid());
				$mh->sendConfirmation($researcher->email, $researcher, $recordInfo);

				Yii::$app->session->addFlash('success', "Success! Mail with verification link was sent to '<a href='mailto:$researcher->email'>$researcher->email</a>'. <p>Your application number is '<b>{$researcher->getUid()}</b>'. We Will get back to you.</p>");

				$body = "<p>Application number is '<b>{$researcher->getUid()}</b>'.</p>";
				$body .= "<p>Data: </p><br><pre>".print_r($researcher->humanReadableInfo(), true)."</pre>";
				$mh->sendToAdmin("New Researcher #{$researcher->getUid()}", $body);

				return $this->goHome();
			} else {
				Yii::$app->session->addFlash('danger','Some errors found - Fix them and resubmit form.');
			}
		}

		return $this->render('becomeResearcher', [
			'researcher' => $researcher,
		]);
	}
}
