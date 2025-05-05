<?php

namespace Tourze\RBAC\Core\Tests\Level0;

use PHPUnit\Framework\TestCase;
use Tourze\RBAC\Core\Level0\Permission;

class PermissionTest extends TestCase
{
    /**
     * 测试 Permission 接口的基本实现
     */
    public function testPermission_basicImplementation(): void
    {
        // 创建 Permission 接口的匿名实现
        $permission = new class implements Permission {
            public function getName(): string
            {
                return 'user.create';
            }

            public function getTitle(): string
            {
                return '创建用户';
            }

            public function getParentPermission(): ?Permission
            {
                return null;
            }
        };

        // 断言基本属性
        $this->assertEquals('user.create', $permission->getName());
        $this->assertEquals('创建用户', $permission->getTitle());
        $this->assertNull($permission->getParentPermission());
    }

    /**
     * 测试 Permission 具有父级权限
     */
    public function testPermission_withParentPermission(): void
    {
        // 创建父级权限
        $parentPermission = new class implements Permission {
            public function getName(): string
            {
                return 'user';
            }

            public function getTitle(): string
            {
                return '用户管理';
            }

            public function getParentPermission(): ?Permission
            {
                return null;
            }
        };

        // 创建子级权限
        $childPermission = new class($parentPermission) implements Permission {
            private Permission $parentPermission;

            public function __construct(Permission $parentPermission)
            {
                $this->parentPermission = $parentPermission;
            }

            public function getName(): string
            {
                return 'user.edit';
            }

            public function getTitle(): string
            {
                return '编辑用户';
            }

            public function getParentPermission(): ?Permission
            {
                return $this->parentPermission;
            }
        };

        // 断言子级权限属性
        $this->assertEquals('user.edit', $childPermission->getName());
        $this->assertEquals('编辑用户', $childPermission->getTitle());
        $this->assertNotNull($childPermission->getParentPermission());
        $this->assertSame($parentPermission, $childPermission->getParentPermission());
        $this->assertEquals('用户管理', $childPermission->getParentPermission()->getTitle());
    }

    /**
     * 测试多级权限嵌套
     */
    public function testPermission_multiLevelHierarchy(): void
    {
        // 创建顶级权限
        $rootPermission = new class implements Permission {
            public function getName(): string
            {
                return 'system';
            }

            public function getTitle(): string
            {
                return '系统';
            }

            public function getParentPermission(): ?Permission
            {
                return null;
            }
        };

        // 创建二级权限
        $midPermission = new class($rootPermission) implements Permission {
            private Permission $parentPermission;

            public function __construct(Permission $parentPermission)
            {
                $this->parentPermission = $parentPermission;
            }

            public function getName(): string
            {
                return 'system.user';
            }

            public function getTitle(): string
            {
                return '用户管理';
            }

            public function getParentPermission(): ?Permission
            {
                return $this->parentPermission;
            }
        };

        // 创建三级权限
        $leafPermission = new class($midPermission) implements Permission {
            private Permission $parentPermission;

            public function __construct(Permission $parentPermission)
            {
                $this->parentPermission = $parentPermission;
            }

            public function getName(): string
            {
                return 'system.user.delete';
            }

            public function getTitle(): string
            {
                return '删除用户';
            }

            public function getParentPermission(): ?Permission
            {
                return $this->parentPermission;
            }
        };

        // 断言三级权限层次结构
        $this->assertEquals('system.user.delete', $leafPermission->getName());
        $this->assertEquals('删除用户', $leafPermission->getTitle());

        $parent = $leafPermission->getParentPermission();
        $this->assertNotNull($parent);
        $this->assertEquals('system.user', $parent->getName());

        $grandparent = $parent->getParentPermission();
        $this->assertNotNull($grandparent);
        $this->assertEquals('system', $grandparent->getName());
        $this->assertNull($grandparent->getParentPermission());
    }
}
