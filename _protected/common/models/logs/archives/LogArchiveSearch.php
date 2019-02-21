<?php

namespace common\models\logs\archives;

use common\models\logs\LogSearch;

/**
 * Class LogArchiveSearch The same logic as in [[LogSearch]] but for table [[LogArchive::tableName()]].
 */
class LogArchiveSearch extends LogSearch
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return LogArchive::tableName();
    }
}
