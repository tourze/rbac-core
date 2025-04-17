<?php

namespace Tourze\RBAC\Core\Level0;

/**
 * 会话（Session）
 * 会话是动态的概念，用户必须通过会话才可以设置角色，是用户与激活的角色之间的映射关系。
 * 会话就是权限跟角色之间的关系描述。
 */
interface Session
{
    /**
     * 会话ID
     */
    public function getId(): int;

    /**
     * 关联用户
     */
    public function getUser(): User;

    /**
     * 关联角色
     * 会话与角色是一对多关系
     *
     * @return Role[]
     */
    public function getRoles(): array;
}
