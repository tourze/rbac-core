<?php

namespace Tourze\RBAC\Core\Level2;

use Tourze\RBAC\Core\Level0\Role;

/**
 * 先决条件角色: 即用户想获得某上级角色,必须先获得其下一级的角色
 *
 * @see https://zhuanlan.zhihu.com/p/73414693
 */
interface PreconditionRole
{
    /**
     * 分配这个角色前，一定需要确保用户已拥有先决条件角色
     */
    public function getPreconditionRole(): ?Role;
}
