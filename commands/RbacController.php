<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\modules\admin\rbac\Rbac;
use yii\console\Exception;
use yii\helpers\ArrayHelper;
use app\modules\user\models\common\User;

class RbacController extends Controller
{
    public function actionInit()
    {
        //Clear all existing roles
        $auth = Yii::$app->getAuthManager();
        $auth->removeAll();
        
        //Roles
        $user = $auth->createRole(Rbac::ROLE_USER);
        $user->description = Rbac::ROLE_USER_DESCRIPTION;
        $auth->add($user);
        
        $editor = $auth->createRole(Rbac::ROLE_EDITOR);
        $editor->description = Rbac::ROLE_EDITOR_DESCRIPTION;
        $auth->add($editor);
        
        $admin = $auth->createRole(Rbac::ROLE_ADMIN);
        $admin->description = Rbac::ROLE_ADMIN_DESCRIPTION;
        $auth->add($admin);
        
        //Permissions
        $editPrices = $auth->createPermission(Rbac::PERMISSION_PRICE_EDIT);
        $editPrices->description = Rbac::PERMISSION_PRICE_EDIT_DESCRIPTION;
        $auth->add($editPrices);
        
        $editGroups = $auth->createPermission(Rbac::PERMISSION_GROUP_EDIT);
        $editGroups->description = Rbac::PERMISSION_GROUP_EDIT_DESCRIPTION;
        $auth->add($editGroups);
        
        $administration = $auth->createPermission(Rbac::PERMISSION_ADMINISTRATION);
        $administration->description = Rbac::PERMISSION_ADMINISTRATION_DESCRIPTION;
        $auth->add($administration);
        
        //Add Permissions to Roles
        $auth->addChild($user, $editPrices);
        $auth->addChild($editor, $editGroups);
        $auth->addChild($admin, $administration);
        
        //Make Roles Hierarchy
        $auth->addChild($editor, $user);
        $auth->addChild($admin, $editor);
        
        //All Done
        $this->stdout('Done!' . PHP_EOL);
    }
    
    /**
     * Adds role to user
     */
    public function actionAssign()
    {
        $username = $this->prompt('Username:', ['required' => true]);
        $user = $this->findModel($username);
        $roleName = $this->select('Role:', ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'));
        $authManager = Yii::$app->getAuthManager();
        $role = $authManager->getRole($roleName);
        $authManager->assign($role, $user->id);
        $this->stdout('Done!' . PHP_EOL);
    }
 
    /**
     * Removes role from user
     */
    public function actionRevoke()
    {
        $username = $this->prompt('Username:', ['required' => true]);
        $user = $this->findModel($username);
        $roleName = $this->select('Role:', ArrayHelper::merge(
            ['all' => 'All Roles'],
            ArrayHelper::map(Yii::$app->authManager->getRolesByUser($user->id), 'name', 'description'))
        );
        $authManager = Yii::$app->getAuthManager();
        if ($roleName == 'all') {
            $authManager->revokeAll($user->id);
        } else {
            $role = $authManager->getRole($roleName);
            $authManager->revoke($role, $user->id);
        }
        $this->stdout('Done!' . PHP_EOL);
    }
 
    /**
     * @param string $username
     * @throws \yii\console\Exception
     * @return User the loaded model
     */
    private function findModel($username)
    {
        if (!$model = User::findOne(['username' => $username])) {
            throw new Exception('User is not found');
        }
        return $model;
    }
}
