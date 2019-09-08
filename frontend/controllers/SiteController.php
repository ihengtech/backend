<?php

namespace frontend\controllers;

use backend\models\FileManage;
use common\helpers\HttpClient;
use frontend\models\FaceDetectForm;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'face-detect' => ['post'],
                    'merchandise-recommend' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
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
        $faceDetectForm = new FaceDetectForm();
        return $this->render('index', [
            'faceDetectForm' => $faceDetectForm,
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionFaceDetect()
    {
        $url = 'http://stage.ihengtech.com:6688/api/image/upload';
        $fileObject = UploadedFile::getInstanceByName('filename');
        $age = null;
        $sex = null;
        if ($fileObject != null) {
            $dir = FileManage::getUploadDir();
            $filename = md5(microtime()) . rand(1, 100) . '.' . $fileObject->getExtension();
            if ($fileObject->saveAs($dir . $filename)) {

                $tmpfile = $dir . $filename;
                if ($fileObject->size > 2000000) {
                    if (FileManage::compressImage($tmpfile, $filename, 0.5) === true) {
                        $tmpfile = $dir . DIRECTORY_SEPARATOR . 'compress' . DIRECTORY_SEPARATOR . $filename;
                    }
                }
                if (is_file($tmpfile)) {
                    $httpClient = new HttpClient();
                    $data = $httpClient->post($url, ['file' => curl_file_create($tmpfile)], [], [], false);
                    Yii::error(Json::encode($data));
                    $content = [];
                    try {
                        $json = Json::decode($data['content']);
                    } catch (\Throwable $e) {
                        $json = [];
                    }
                    if (isset($json['content'])) {
                        try {
                            $content = Json::decode($json['content']);
                        } catch (\Throwable $e) {
                            $content = [];
                        }
                    }
                    $age = isset($content['age']['0']) ? $content['age']['0'] : null;
                    $sex = isset($content['gender']['0']) ? $content['gender']['0'] : null;
                    if ($sex == 1) {
                        $sex = 0;
                    } elseif ($sex !== null && $sex == 0) {
                        $sex = 1;
                    }
                }
            }
        }
        $result = [];
        if ($sex !== null) {
            $result['性别'] = $sex == 0 ? '男' : '女';
        }
        if ($age !== null) {
            $result['年龄'] = $age . '岁';
        }
        if ($result === []) {
            $result['无法'] ='识别';
        }
        return $this->asJson([
            'code' => 0,
            'message' => null,
            'data' => [
                'image' => '/upload/images/' . $filename,
                'result' => $result,
                'params' => [
                    'sex' => $sex,
                    'age' => $age,
                ]
            ],
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionMerchandiseRecommend()
    {
        $age = intval(Yii::$app->request->post('age'));
        $sex = intval(Yii::$app->request->post('sex'));
        $url = 'http://stage.ihengtech.com:6688/api/commodity/get/url/%7Bsex%7D/%7Bage%7D?age=' . $age . '&sex=' . $sex;
        $httpClient = new HttpClient();
        $data = $httpClient->post($url);
        Yii::error(Json::encode($data));
        try {
            $json = Json::decode($data['content']);
        } catch (\Throwable $e) {
            $json = [];
        }
        $resultUrl = isset($json['content']) ? $json['content'] : 'http://www.jd.com';
        $result = [
            [
                'id' => '1',
                'url' => $resultUrl,
                'image' => null,
            ],
        ];
        return $this->asJson([
            'code' => 0,
            'message' => null,
            'data' => $result,
        ]);
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
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
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
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
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
        } catch (InvalidArgumentException $e) {
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

    /**
     * Verify email address
     *
     * @param string $token
     * @return yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
