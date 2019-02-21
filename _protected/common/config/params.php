<?php

use common\services\deleter\ModelsToDeleteService;

return [

    //------------------------//
    // SYSTEM SETTINGS
    //------------------------//

    /**
     * Registration Needs Activation.
     *
     * If set to true, upon registration, users will have to activate their accounts using email account activation.
     */
    'rna' => false,

    /**
     * Login With Email.
     *
     * If set to true, users will have to login using email/password combo.
     */
    'lwe' => false, 

    /**
     * Force Strong Password.
     *
     * If set to true, users will have to use passwords with strength determined by StrengthValidator.
     */
    'fsp' => false,

    /**
     * Set the password reset token expiration time.
     */
    'user.passwordResetTokenExpire' => 3600,

    /**
     * Set the list of usernames that we do not want to allow to users to take upon registration or profile change.
     */
    'user.spamNames' => 'admin|superadmin|creator|thecreator|username',

    //------------------------//
    // EMAILS
    //------------------------//

    /**
     * Email used in contact form.
     * Users will send you emails to this address.
     */
    'adminEmail' => 'admin@example.com', 

    /**
     * Email used in sign up form, when we are sending email with account activation link.
     * You will send emails to users from this address.
     */
    'supportEmail' => 'support@example.com',

    ModelsToDeleteService::PREVENT_DELETING_LAST_DAYS => 1,
    /**
     * List of models which need to be deleted periodically.
     * If you need to manage new model to delete, just add it here as new element, as key use model name.
     */
    ModelsToDeleteService::MODELS_TO_DELETE => [
        \common\models\logs\Log::class => [
            ModelsToDeleteService::MODEL_NAME_KEY => \common\models\logs\Log::class,
            ModelsToDeleteService::LOG_TIME_ATTR_KEY => 'log_time',
            ModelsToDeleteService::LOG_TIME_MULTIPLIER_KEY => 1,
            ModelsToDeleteService::ALLOWED_TO_DELETE => false,
        ],
        \common\models\logs\archives\LogArchive::class => [
            ModelsToDeleteService::MODEL_NAME_KEY => \common\models\logs\archives\LogArchive::class,
            ModelsToDeleteService::LOG_TIME_ATTR_KEY => 'log_time',
            ModelsToDeleteService::LOG_TIME_MULTIPLIER_KEY => 1,
            ModelsToDeleteService::ALLOWED_TO_DELETE => true,
        ],
    ],
];
