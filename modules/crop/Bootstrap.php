<?php

namespace app\modules\crop;
 
use yii\base\BootstrapInterface;
 
class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->i18n->translations['modules/crop/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'forceTranslation' => true,
            'basePath' => '@app/modules/crop/messages',
            'fileMap' => [
                'modules/crop/crop' => 'crop.php',
            ],
        ];
    }
}
