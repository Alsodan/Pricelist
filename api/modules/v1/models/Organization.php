<?php

namespace app\api\modules\v1\models;

use app\modules\organization\models\Organization as BaseOrganization;

/**
 * This is the model class for table "{{%organization}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $file
 * @property integer $warehouse_id
 * @property integer $status
 * @property integer $sort
 */
class Organization extends BaseOrganization
{
    /*
     * Возвращаемые поля в REST API
     */
    public function fields() {
        return [
            'id',
            'title',
            'file'
        ];
    }
}
