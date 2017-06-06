<?php

namespace app\modules\product;
 
use yii\base\BootstrapInterface;
 
class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->i18n->translations['modules/product/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'forceTranslation' => true,
            'basePath' => '@app/modules/product/messages',
            'fileMap' => [
                'modules/product/product' => 'product.php',
            ],
        ];
    }
}
