<?php

namespace Tourze\RBAC\Core\Service;

use Tourze\RBAC\Core\Level0\Role;

interface RoleLoaderInterface
{
    /**
     * 根据角色名读取角色模型
     */
    public function loadUserByName(string $name): ?Role;

    /**
     * 读取有效的角色列表
     *
     * @return array|Role[]
     */
    public function loadValidRoles(): array;
}
