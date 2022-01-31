<?php

namespace TwinElements\SettingsBundle;

use TwinElements\AdminBundle\Menu\AdminMenuInterface;
use TwinElements\AdminBundle\Menu\MenuItem;
use TwinElements\AdminBundle\Role\AdminUserRole;

class AdminMenu implements AdminMenuInterface
{
    public function getItems()
    {
        return [
            MenuItem::newInstance('admin_settings.settings', 'settings_index', [], 30, AdminUserRole::ROLE_ADMIN),
        ];
    }
}
