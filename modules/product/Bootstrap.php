<?php

namespace app\modules\warehouse;
 
use yii\base\BootstrapInterface;
 
class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->i18n->translations['modules/warehouse/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'forceTranslation' => true,
            'basePath' => '@app/modules/warehouse/messages',
            'fileMap' => [
                'modules/warehouse/warehouse' => 'warehouse.php',
            ],
        ];
    }
}
