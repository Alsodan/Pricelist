<?php

namespace app\modules\region;
 
use yii\base\BootstrapInterface;
 
class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->i18n->translations['modules/region/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'forceTranslation' => true,
            'basePath' => '@app/modules/region/messages',
            'fileMap' => [
                'modules/region/region' => 'region.php',
            ],
        ];
    }
}
