<?php

namespace app\components\auth;

use Yii;
use yii\rbac\Assignment;
use yii\rbac\PhpManager;
use app\modules\user\models\common\User;

class AuthManager extends PhpManager
{
    /**
     * Get User Roles assignments
     * @param in $userId
     * @return array
     */
    public function getAssignments($userId) 
    {
        if ($userId && $user = $this->getUser($userId)) {
            $assignment = new Assignment();
            $assignment->userId = $userId;
            $assignment->roleName = $user->role;
            
            return [$assignment->roleName => $assignment];
        }
        
        return [];
    }
    
    /**
     * Get User Role assignment
     * @param string $roleName
     * @param int $userId
     * @return null|Assignment
     */
    public function getAssignment($roleName, $userId)
    {
        if ($userId && $user = $this->getUser($userId)) {
            if ($user->role == $roleName) {
                $assignment = new Assignment();
                $assignment->userId = $userId;
                $assignment->roleName = $user->role;
                
                return $assignment;
            }
        }
        
        return null;
    }

    /**
     * Get User by ID
     * @param type $userId
     * @return null|\yii\web\IdentityInterface|User
     */
    private function getUser($userId)
    {
        //Get User component with no Exception
        $webUser = Yii::$app->get('user', false);
        //Return User identity or search User by ID
        if ($webUser && !$webUser->isGuest && $webUser->id == $userId) {
            return $webUser->identity;
        } else {
            return User::findOne($userId);
        }
    }
    
    /**
     * Get User Ids by Role name
     * @param string $roleName
     * @return array
     */
    public function getUserIdsByRole($roleName)
    {
        return User::find()
                ->where(['role' => $roleName])
                ->select('id')
                ->column();
    }
    
    /**
     * Assign Role to User
     * @param yii\rbac\Role $role
     * @param int $userId
     * @return null|Assignment
     */
    public function assign($role, $userId)
    {
        if ($userId && $user = $this->getUser($userId)) {
            $assignment = new Assignment([
                'userId' => $userId,
                'roleName' => $role->name,
                'createdAt' => time(),
            ]);
            
            $this->setRole($user, $role->name);
            
            return $assignment;
        }
        
        return null;
    }
    
    /**
     * Remove Role assignment from User
     * @param yii\rbac\Role $role
     * @param int $userId
     * @return boolean
     */
    public function revoke($role, $userId)
    {
        if ($userId && $user = $this->getUser($userId)) {
            if ($user->role == $role->name) {
                $this->setRole($user, null);
                
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Remove all Roles assignments from User
     * @param int $userId
     * @return boolean
     */
    public function revokeAll($userId)
    {
        if ($userId && $user = $this->getUser($userId)) {
            $this->setRole($user, null);
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Set User 'role' attribute
     * @param User $user
     * @param string $roleName
     */
    private function setRole(User $user, $roleName)
    {
        $user->role = $roleName;
        $user->updateAttributes(['role' => $roleName]);
    }
}
