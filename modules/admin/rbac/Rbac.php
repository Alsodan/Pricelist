<?php

namespace app\modules\admin\rbac;

/**
 * RBAC constants
 */

class Rbac 
{
    const ROLE_USER = 'roleUser';
    const ROLE_USER_DESCRIPTION = 'Менеджер';
    const ROLE_EDITOR = 'roleEditor';
    const ROLE_EDITOR_DESCRIPTION = 'Руководитель';
    const ROLE_ADMIN = 'roleAdmin';
    const ROLE_ADMIN_DESCRIPTION = 'Администратор';
    
    const PERMISSION_PRICE_EDIT = 'permPriceEdit';
    const PERMISSION_PRICE_EDIT_DESCRIPTION = 'Редактировать цены';
    const PERMISSION_GROUP_EDIT = 'permGroupEdit';
    const PERMISSION_GROUP_EDIT_DESCRIPTION = 'Редактировать группы';
    const PERMISSION_ADMINISTRATION = 'permAdmininstration';
    const PERMISSION_ADMINISTRATION_DESCRIPTION = 'Администрирование';
}
