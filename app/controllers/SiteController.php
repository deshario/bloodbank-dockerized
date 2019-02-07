<?php
namespace app\controllers;

use app\components\AccessRule;
use app\models\Managers;
use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Notification;
use paragraph1\phpFCM\Recipient\Device;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\LoginForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\SignupForm;
use app\models\ContactForm;
use kartik\growl\Growl;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className()
                ],
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'], // only allowed for guest
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'], // only allowed for logged in users
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        //$this->layout = "main-signup";
        return $this->render('index');
    }

    public function actionApi(){

        return $this->renderPartial('api');
    }

    public function actionInfo(){

        return $this->renderPartial('phpinfo');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //return $this->goBack();
            $MStatus = Yii::$app->user->identity->status;
            if($MStatus == Managers::STATUS_DELETED){
                Yii::$app->getSession()->setFlash('deleted', [
                    'type' => Growl::TYPE_DANGER,
                    'duration' => 5000,
                    'icon' => 'fa fa-close',
                    'title' => 'Account DeActivated !',
                    'message' => 'Please contact administrator to activate it.',
                    'positonY' => 'top',
                    'positonX' => 'right'
                ]);
                return $this->redirect(['login']);
            }elseif ($MStatus == Managers::STATUS_WAITING){
                Yii::$app->getSession()->setFlash('waiting', [
                    'type' =>  Growl::TYPE_INFO,
                    'duration' => 5000,
                    'icon' => 'fa fa-refresh fa-spin',
                    'title' => 'Your account is InActive !',
                    'message' => 'Please contact administrator to activate it.',
                    'positonY' => 'top',
                    'positonX' => 'right'
                ]);
                return $this->redirect(['login']);
            }else{
                $MRoles = Yii::$app->user->identity->roles;
                $logged_user = Yii::$app->user->identity->username;
                Yii::$app->getSession()->setFlash('login_success', [
                    'type' =>  Growl::TYPE_SUCCESS,
                    'duration' => 5000,
                    'icon' => 'fa fa-user-o',
                    'title' => ' Hey '.$logged_user.' !',
                    'message' => 'Welcome to Cloud BloodBank.',
                    'positonY' => 'bottom',
                    'positonX' => 'right'
                ]);
                if($MRoles == Managers::ROLE_MANAGER){
//                    return $this->redirect('index.php?r=blood-requests/index');
                    return $this->redirect(['/blood-requests']);
                }elseif ($MRoles == Managers::ROLE_ADMIN){
                    return $this->redirect(['/managers']);
                }
            }

        } else {
            $model->password = '';
            $this->layout = "main-login";
            return $this->render('theme_login', [
                'model' => $model,
            ]);
        }
    }

    public function actionHome(){
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }else {
            $MRoles = Yii::$app->user->identity->roles;
            if ($MRoles == Managers::ROLE_MANAGER) {
                return $this->redirect(['/blood-requests']);
            } elseif ($MRoles == Managers::ROLE_ADMIN) {
                return $this->redirect(['/managers']);
            }
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }
        $this->layout = "main-signup";
        return $this->render('theme_signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionFault()
    {
        $exception = Yii::$app->errorHandler->exception;

        if ($exception !== null) {
            $statusCode = $exception->statusCode;
            $name = $exception->getName();
            $message = $exception->getMessage();

//            Yii::$app->getSession()->setFlash('alert1', [
//                'type' => 'info',
//                'duration' => 3000,
//                'icon' => 'fa fa-envelope-o',
//                'title' => 'Incoming Request',
//                'message' => 'test hahah',
//                'positonY' => 'top',
//                'positonX' => 'right'
//            ]);

            return $this->render('fault', [
                'exception' => $exception,
                'statusCode' => $statusCode,
                'name' => $name,
                'message' => $message
            ]);
        }
    }

    public function actionNotification(){
        //$apiKey = 'AAAAiV_lG7U:APA91bFbFIpHkUu9AFXl6Zt00XtExVxCVOZxMg038ZzBD_BQggyKaikRXhxRo8OTOIu8gUWfOEOSI8A-2adhI6PQG5smzNAzVqd06ISPjI7MdpXnRPwybBEXARriurAf9brFlksuGfLg';
        $apiKey = 'AAAAtahKkhs:APA91bGh-bWgBw-GijtUGy_usffLLrX2D1y2YT5mRwdFsKKKzbh7pQvb-Fz0FPbTcnMzYTR2VAFi34U2UACuu7HatKMfFTEwshGPrLUo4RRUxRUepiOJzKwRrozVvCQbjzS6kLfO8Jlb';
        $client = new Client();
        $client->setApiKey($apiKey);
        $client->injectHttpClient(new \GuzzleHttp\Client());

        $note = new Notification('YII2 PUSH', 'NOTIFICATION FROM YII2');
        $note->setIcon('R.mipmap.ic_launcher')
            ->setColor('#ffffff')
            ->setBadge(1);

        $message = new Message();
        //$message->addRecipient(new Device('e4xTowlSLfw:APA91bHGFFBeg8e5Ah4ayZpZVNEv2NmqrhqDaeYqP471-dRP4dkgQAhU4utGqMhRv86SYEg4CMQDoQBQ0LJ2tceGowagdrNm0yZbUdye4FGoRkLR70J4ytm7MPH7t42iQVSEkS-b_bgopwVdrrHdNRGnMLf6p_Ba4A'));
        $message->addRecipient(new Device('eN-ovv30CN0:APA91bGtSH14MNlmjIUiuPk13-0ruokyWMF3tv6anbCtRbqLlnIxDxkuG9--u_MTWQMDPEisiHtiJW0il7hG9tKpzRmuLw6DovCTpxLFDJrSQsobAtVJ9Bvr4tw51-l8XcrygtMgwiLR'));
        $message->setNotification($note)
            ->setData(array('someId' => 111));

        $response = $client->send($message);
        var_dump($response->getStatusCode());
    }
}