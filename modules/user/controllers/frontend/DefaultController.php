<?php

namespace app\modules\user\controllers\frontend;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use app\modules\user\forms\common\LoginForm;
use app\modules\user\forms\frontend\SignupForm;
use app\modules\user\forms\frontend\EmailConfirmForm;
use app\modules\user\forms\frontend\PasswordResetForm;
use app\modules\user\forms\frontend\PasswordResetRequestForm;
use app\modules\user\Module;

class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'login'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ]
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
     * CAPTCHA action
     * 
     * @return array
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            //return $this->goHome();
            return $this->redirect('/administrator');
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect('/administrator')/*$this->goBack()*/;
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Log out action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        //return $this->goHome();
        return $this->redirect('/administrator');
    }
    
    /**
     * Sign up action.
     *
     * @return string
     */
    /*public function actionSignup()
    {
        $model = new SignupForm($this->module->defaultRole);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->signup()) {
                Yii::$app->getSession()->setFlash('success', Module::t('user', 'USER_EMAIL_WAS_SENT_TO_CONFIRM {email}', ['email' => $model->email]));
                return $this->refresh();
            }
        }
 
        return $this->render('signup', [
            'model' => $model,
        ]);
    }*/
    
    /**
     * Email confirmation action.
     *
     * @return string
     */
    public function actionEmailConfirm($token)
    {
        try {
            $model = new EmailConfirmForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
 
        if ($model->confirmEmail()) {
            Yii::$app->getSession()->setFlash('success', Module::t('user', 'USER_EMAIL_CONFIRMED'));
        } else {
            Yii::$app->getSession()->setFlash('error', Module::t('user', 'USER_EMAIL_CONFIRM_ERROR'));
        }
 
        return $this->render('emailConfirm');
    }
    
    /**
     * Show Password reset form action.
     *
     * @return string
     */
    public function actionPasswordResetRequest()
    {
        $model = new PasswordResetRequestForm($this->module->passwordResetTokenExpire);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', Module::t('user', 'USER_PASSWORD_RESET_EMAIL_WAS_SENT_TO {email}', ['email' => $model->email]));
            } else {
                Yii::$app->getSession()->setFlash('error', Module::t('user', 'USER_EMAIL_SEND_ERROR'));
            }
            return $this->refresh();
        }
 
        return $this->render('passwordResetRequest', [
            'model' => $model,
        ]);
    }
    
    /**
     * Password reset action.
     *
     * @return string
     */    
    public function actionPasswordReset($token)
    {
        try {
            $model = new PasswordResetForm($token, $this->module->passwordResetTokenExpire);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
 
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', Module::t('user', 'USER_PASSWORD_RESET_SUCCESS'));
 
            return $this->render('passwordReset', [
                'model' => $model,
            ]);
        }
 
        return $this->render('passwordReset', [
            'model' => $model,
        ]);
    }
    
    /**
     * Redirect from /user to /user/profile
     * 
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect(['profile/index'], 301);
    }
}