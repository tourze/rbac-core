<?php

namespace Tourze\RBAC\Core\Tests\Level0;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\RBAC\Core\Level0\Role;

/**
 * @internal
 */
#[CoversClass(Role::class)]
final class RoleTest extends TestCase
{
    /**
     * 测试 Role 接口的基本实现
     */
    public function testRoleBasicImplementation(): void
    {
        // 创建 Role 接口的匿名实现
        $role = new class implements Role {
            public function getName(): string
            {
                return 'admin';
            }

            public function getTitle(): string
            {
                return '管理员';
            }
        };

        // 断言基本属性
        $this->assertEquals('admin', $role->getName());
        $this->assertEquals('管理员', $role->getTitle());
    }

    /**
     * 测试多个角色创建
     */
    public function testRoleMultipleRoles(): void
    {
        // 创建普通用户角色
        $userRole = new class implements Role {
            public function getName(): string
            {
                return 'user';
            }

            public function getTitle(): string
            {
                return '普通用户';
            }
        };

        // 创建编辑者角色
        $editorRole = new class implements Role {
            public function getName(): string
            {
                return 'editor';
            }

            public function getTitle(): string
            {
                return '编辑者';
            }
        };

        // 创建审核员角色
        $reviewerRole = new class implements Role {
            public function getName(): string
            {
                return 'reviewer';
            }

            public function getTitle(): string
            {
                return '审核员';
            }
        };

        // 断言各角色属性
        $this->assertEquals('user', $userRole->getName());
        $this->assertEquals('普通用户', $userRole->getTitle());

        $this->assertEquals('editor', $editorRole->getName());
        $this->assertEquals('编辑者', $editorRole->getTitle());

        $this->assertEquals('reviewer', $reviewerRole->getName());
        $this->assertEquals('审核员', $reviewerRole->getTitle());
    }

    /**
     * 测试特殊字符角色名
     */
    public function testRoleSpecialCharactersInName(): void
    {
        // 创建含特殊字符的角色名
        $role = new class implements Role {
            public function getName(): string
            {
                return 'user:super_admin@123';
            }

            public function getTitle(): string
            {
                return '超级管理员';
            }
        };

        // 断言特殊字符角色名正确处理
        $this->assertEquals('user:super_admin@123', $role->getName());
        $this->assertEquals('超级管理员', $role->getTitle());
    }

    /**
     * 测试空标题角色
     */
    public function testRoleEmptyTitle(): void
    {
        // 创建空标题的角色
        $role = new class implements Role {
            public function getName(): string
            {
                return 'anonymous';
            }

            public function getTitle(): string
            {
                return '';
            }
        };

        // 断言空标题处理
        $this->assertEquals('anonymous', $role->getName());
        $this->assertEquals('', $role->getTitle());
        $this->assertEmpty($role->getTitle());
    }
}
