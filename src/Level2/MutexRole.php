<?php

namespace Tourze\RBAC\Core\Level2;

use Tourze\RBAC\Core\Level0\Role;

/**
 * 同一用户只能分配到一组互斥角色集合中至多一个角色，支持责任分离的原则。
 * 互斥角色是指各自权限互相制约的两个角色。
 * 比如财务部有会计和审核员两个角色,他们是互斥角色,那么用户不能同时拥有这两个角色,体现了职责分离原则
 *
 * @see https://zhuanlan.zhihu.com/p/73414693
 */
interface MutexRole
{
    /**
     * 放回当前角色的互斥角色
     *
     * @return array|Role[]
     */
    public function getMutexRoles(): array;
}
