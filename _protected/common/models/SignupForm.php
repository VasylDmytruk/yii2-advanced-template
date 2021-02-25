<?php

namespace common\models;

use common\exceptions\ValidatorException;
use common\rbac\helpers\RbacHelper;
use himiklab\yii2\recaptcha\ReCaptchaValidator;
use kartik\password\StrengthValidator;
use Yii;
use yii\base\Model;

/**
 * Class SignupForm
 */
class SignupForm extends Model
{
    /**
     * @var string|null
     */
    protected $username;
    /**
     * @var string|null
     */
    protected $email;
    /**
     * @var string|null
     */
    protected $password;
    /**
     * @var int|null
     */
    protected $status;
    /**
     * @var string
     */
    public $reCaptcha;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            ['username', 'filter', 'filter' => 'trim'],
//            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'match', 'not' => true,
                // we do not want to allow users to pick one of spam/bad usernames
                'pattern' => '/\b(' . Yii::$app->params['user.spamNames'] . ')\b/i',
                'message' => Yii::t('app', 'It\'s impossible to have that username.')],
            ['username', 'unique', 'targetClass' => '\common\models\User',
                'message' => Yii::t('app', 'This phone has already been taken.')],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User',
                'message' => Yii::t('app', 'This email address has already been taken.')],

            ['password', 'required'],
            // use passwordStrengthRule() method to determine password strength
            $this->passwordStrengthRule(),

            // on default scenario, user status is set to active
            ['status', 'default', 'value' => User::STATUS_ACTIVE, 'on' => 'default'],
            // status is set to not active on rna (registration needs activation) scenario
            ['status', 'default', 'value' => User::STATUS_INACTIVE, 'on' => 'rna'],
            // status has to be integer value in the given range. Check User model.
            ['status', 'in', 'range' => [User::STATUS_INACTIVE, User::STATUS_ACTIVE]]
        ];

        if (YII_ENV_PROD) {
            $rules[] = [['reCaptcha'], 'required'];
            $rules[] = [
                ['reCaptcha'],
                ReCaptchaValidator::class,
                'uncheckedMessage' => Yii::t('app', 'Please confirm that you are not a bot.'),
            ];
        }

        return $rules;
    }

    /**
     * Set password rule based on our setting value ( Force Strong Password ).
     *
     * @return array Password strength rule
     */
    private function passwordStrengthRule()
    {
        // get setting value for 'Force Strong Password'
        $fsp = Yii::$app->params['fsp'];

        // password strength rule is determined by StrengthValidator
        // presets are located in: vendor/kartik-v/yii2-password/presets.php
        $strong = [['password'], StrengthValidator::class, 'preset' => 'normal'];

        // use normal yii rule
        $normal = ['password', 'string', 'min' => 6];

        // if 'Force Strong Password' is set to 'true' use $strong rule, else use $normal rule
        return ($fsp) ? $strong : $normal;
    }

    /**
     * Returns the attribute labels.
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'email' => Yii::t('app', 'Email'),
        ];
    }

    /**
     * Signs up the user.
     * If scenario is set to "rna" (registration needs activation), this means
     * that user need to activate his account using email confirmation method.
     *
     * @return User|null The saved model or null if saving fails.
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \Throwable
     */
    public function signup(): ?User
    {
        $user = $this->getNewUserInstance();

        $user->username = $this->getUsernameToSet();
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->status = $this->status;

        // if scenario is "rna" ( Registration Needs Activation ) we will generate account activation token
        if ($this->scenario === 'rna') {
            $user->generateAccountActivationToken();
        }

        $this->beforeUserSave($user);

        // if user is saved and role is assigned return user object
        $result = null;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($user->save() && RbacHelper::assignRole($user->getId())) {
                $this->afterUserSave($user);
                $result = $user;
            } elseif ($user->hasErrors()) {
                $errors = $this->mergeErrors($user);
                throw new ValidatorException(Yii::t('app', 'Validation error'), $errors);
            }

            $transaction->commit();
        } catch (\Throwable $e) {
            Yii::error('Can not save user, rollback.' . PHP_EOL . $e->getMessage(), self::class);
            $transaction->rollBack();
            throw $e;
        }

        return $result;
    }

    /**
     * Merges user errors with SingupForm. Returns SingupForm merged errors.
     * @param User $user
     * @return array Returns SingupForm merged errors.
     */
    protected function mergeErrors($user): array
    {
        foreach ($user->errors as $attribute => $errors) {
            if ($this->hasProperty($attribute)) {
                $this->addErrors([$attribute => $errors]);
            }
        }

        return $this->errors;
    }

    /**
     * Gets new user instance.
     * @return User
     */
    protected function getNewUserInstance()
    {
        return new User();
    }

    /**
     * Gets value to set as username of User model.
     * @return string|mixed
     */
    protected function getUsernameToSet()
    {
        return $this->username;
    }

    /**
     * Does actions before user save.
     * @param User $user
     */
    protected function beforeUserSave($user): void
    {

    }

    /**
     * Does actions after user save
     * @param User $user
     */
    protected function afterUserSave($user): void
    {
    }

    /**
     * Sends email to registered user with account activation link.
     *
     * @param object $user Registered user.
     * @return bool         Whether the message has been sent successfully.
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function sendAccountActivationEmail($user)
    {
        // TODO implement
        return true;
//        /* @var UserMailer $userMailer */
//        $userMailer = Yii::$container->get(UserMailer::class);
//
//        return $userMailer->sendAccountActivationEmail($user);
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int|null $status
     */
    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }
}
