<?php

namespace Tourze\RBAC\Core\Level0;

/**
 * 角色（Role）
 *
 * 是指一组系统权限的集合。
 */
interface Role
{
    /**
     * 角色名
     */
    public function getName(): string;

    /**
     * 角色标题
     */
    public function getTitle(): string;
}
