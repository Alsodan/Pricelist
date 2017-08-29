<?php

namespace app\modules\organization;
 
use yii\base\BootstrapInterface;
 
class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->i18n->translations['modules/organization/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'forceTranslation' => true,
            'basePath' => '@app/modules/organization/messages',
            'fileMap' => [
                'modules/organization/organization' => 'organization.php',
            ],
        ];
    }
}
