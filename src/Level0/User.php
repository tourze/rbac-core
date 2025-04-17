<?php

namespace Tourze\RBAC\Core\Level0;

/**
 * 用户（User）
 */
interface User
{
    /**
     * 用户有多个角色
     *
     * @return Role[]
     */
    public function getRoles(): array;

    /**
     * 一个用户会有N个会话
     * Session在RBAC中是一个比较隐晦的元素，准确来说，每个Session都是一个映射，一个用户到多个Role角色的映射。当一个用户激活它所有角色的一个子集时会建立一个Session会话。每个Session会话和单个User用户可以关联到一个或多个Session会话。
     *
     * @return Session[]
     */
    public function getSessions(): array;
}
