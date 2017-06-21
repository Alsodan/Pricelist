<?php

namespace app\components\grid;

use app\modules\admin\rbac\Rbac;
use yii\grid\DataColumn;
use yii\helpers\Html;
use Yii;

class RoleColumn extends DataColumn
{
    public $roleCss = [
        Rbac::ROLE_USER => 'success',
        Rbac::ROLE_EDITOR => 'primary',
        Rbac::ROLE_ADMIN => 'danger',
    ];
 
    protected function renderDataCellContent($model, $key, $index)
    {
        $value = $this->getDataCellValue($model, $key, $index);
        $label = $value ? $this->getRoleLabel($value) : $value;
        $class = $this->roleCss[$value];
        $html = Html::tag('span', Html::encode($label), ['class' => 'label label-' . $class]);
        return $value === null ? $this->grid->emptyCell : $html;
    }
 
    private function getRoleLabel($roleName)
    {
        if ($role = Yii::$app->authManager->getRole($roleName)) {
            return $role->description;
        } else {
            return $roleName;
        }
    }
}
