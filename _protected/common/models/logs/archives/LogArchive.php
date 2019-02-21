<?php

namespace common\models\logs\archives;

use common\models\logs\Log;

/**
 * This is the model class for table "log_archive". Extends logic of [[Log]].
 */
class LogArchive extends Log
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_archive';
    }
}
