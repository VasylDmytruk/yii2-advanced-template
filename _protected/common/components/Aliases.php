<?php
namespace common\components;

use yii\base\Component;
use Yii;

/**
 * Component where you can define your aliases.
 * 
 * This component is bootstrap-ed in your common/config/main.php configuration file.
 * It is good to make aliases here so we can use predefined aliases 
 * and other settings made by application configuration.
 *
 * @author Nenad Zivkovic <nenad@freetuts.org>
 * @since 2.3.0 <improved template version>
 */
class Aliases extends Component
{
    public function init() 
    {
        Yii::setAlias('@frontend', Yii::getAlias('@approot').'/_protected/frontend/');
        Yii::setAlias('@backend', Yii::getAlias('@approot').'/_protected/backend/');
        Yii::setAlias('@console', Yii::getAlias('@approot').'/_protected/console/');
        Yii::setAlias('@tests', Yii::getAlias('@approot').'/_protected/tests/');
        Yii::setAlias('@uploads', Yii::getAlias('@approot').'/uploads/');

        // we dont want to try to create theme alias in console application, since we do not use that there
        if (Yii::$app->view->theme) {
            Yii::setAlias('@themes', Yii::$app->view->theme->baseUrl);
        }
    }
}