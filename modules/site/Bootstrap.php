<?php

namespace app\modules\site;
 
use yii\base\BootstrapInterface;
 
class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->i18n->translations['modules/site/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'forceTranslation' => true,
            'basePath' => '@app/modules/site/messages',
            'fileMap' => [
                'modules/site/site' => 'site.php',
            ],
        ];
    }
}
