<?php

namespace app\modules\group;
 
use yii\base\BootstrapInterface;
 
class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->i18n->translations['modules/group/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'forceTranslation' => true,
            'basePath' => '@app/modules/group/messages',
            'fileMap' => [
                'modules/group/group' => 'group.php',
            ],
        ];
    }
}
