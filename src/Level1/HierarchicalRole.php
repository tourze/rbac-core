<?php

namespace Tourze\RBAC\Core\Level1;

use Tourze\RBAC\Core\Level0\Role;

/**
 * 具有继承功能的角色。
 * 当前角色拥有继承角色所有的许可。
 * 要注意，继承角色并不一定是上下级关系。
 *
 * @see https://zhuanlan.zhihu.com/p/73414693
 */
interface HierarchicalRole
{
    /**
     * 继承角色
     *
     * @return Role[]
     */
    public function getHierarchicalRoles(): array;
}
