<?php

namespace Tourze\RBAC\Core\Tests\Level2;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\RBAC\Core\Level0\Role;
use Tourze\RBAC\Core\Level2\PreconditionRole;

/**
 * @internal
 */
#[CoversClass(PreconditionRole::class)]
final class PreconditionRoleTest extends TestCase
{
    /**
     * 测试 PreconditionRole 接口的基本实现
     */
    public function testPreconditionRoleBasicImplementation(): void
    {
        // 创建前置条件角色
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

        // 创建需要前置条件的角色
        $editorRole = new class($userRole) implements Role, PreconditionRole {
            private Role $preconditionRole;

            public function __construct(Role $preconditionRole)
            {
                $this->preconditionRole = $preconditionRole;
            }

            public function getName(): string
            {
                return 'editor';
            }

            public function getTitle(): string
            {
                return '编辑者';
            }

            public function getPreconditionRole(): Role
            {
                return $this->preconditionRole;
            }
        };

        // 断言前置条件角色关系
        $this->assertEquals('editor', $editorRole->getName());
        $this->assertEquals('编辑者', $editorRole->getTitle());
        $this->assertNotNull($editorRole->getPreconditionRole());
        $this->assertSame($userRole, $editorRole->getPreconditionRole());
        $this->assertEquals('user', $editorRole->getPreconditionRole()->getName());
    }

    /**
     * 测试多级前置条件角色
     */
    public function testPreconditionRoleChainedPreconditions(): void
    {
        // 创建基础角色
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

        // 创建需要用户角色的编辑者角色
        $editorRole = new class($userRole) implements Role, PreconditionRole {
            private Role $preconditionRole;

            public function __construct(Role $preconditionRole)
            {
                $this->preconditionRole = $preconditionRole;
            }

            public function getName(): string
            {
                return 'editor';
            }

            public function getTitle(): string
            {
                return '编辑者';
            }

            public function getPreconditionRole(): Role
            {
                return $this->preconditionRole;
            }
        };

        // 创建需要编辑者角色的管理员角色
        $adminRole = new class($editorRole) implements Role, PreconditionRole {
            private Role $preconditionRole;

            public function __construct(Role $preconditionRole)
            {
                $this->preconditionRole = $preconditionRole;
            }

            public function getName(): string
            {
                return 'admin';
            }

            public function getTitle(): string
            {
                return '管理员';
            }

            public function getPreconditionRole(): Role
            {
                return $this->preconditionRole;
            }
        };

        // 断言多级前置条件角色
        $this->assertEquals('admin', $adminRole->getName());
        $this->assertEquals('管理员', $adminRole->getTitle());

        $editorFromAdmin = $adminRole->getPreconditionRole();
        $this->assertNotNull($editorFromAdmin);
        $this->assertEquals('editor', $editorFromAdmin->getName());
        $this->assertInstanceOf(PreconditionRole::class, $editorFromAdmin);

        $userFromEditor = $editorFromAdmin->getPreconditionRole();
        $this->assertNotNull($userFromEditor);
        $this->assertEquals('user', $userFromEditor->getName());
    }

    /**
     * 测试没有前置条件的角色
     */
    public function testPreconditionRoleWithNoPrecondition(): void
    {
        // 创建没有前置条件的角色
        $baseRole = new class implements Role, PreconditionRole {
            public function getName(): string
            {
                return 'base';
            }

            public function getTitle(): string
            {
                return '基础角色';
            }

            public function getPreconditionRole(): ?Role
            {
                return null;
            }
        };

        // 断言无前置条件角色
        $this->assertEquals('base', $baseRole->getName());
        $this->assertEquals('基础角色', $baseRole->getTitle());
        $this->assertNull($baseRole->getPreconditionRole());
    }

    /**
     * 测试前置条件角色的循环引用（理论上不应该存在）
     */
    public function testPreconditionRoleCircularReference(): void
    {
        // 创建角色1，暂时无前置条件
        $role1 = new class implements Role, PreconditionRole {
            private ?Role $preconditionRole = null;

            public function getName(): string
            {
                return 'role1';
            }

            public function getTitle(): string
            {
                return '角色1';
            }

            public function setPreconditionRole(Role $role): void
            {
                $this->preconditionRole = $role;
            }

            public function getPreconditionRole(): ?Role
            {
                return $this->preconditionRole;
            }
        };

        // 创建角色2，前置条件为角色1
        $role2 = new class($role1) implements Role, PreconditionRole {
            private Role $preconditionRole;

            public function __construct(Role $preconditionRole)
            {
                $this->preconditionRole = $preconditionRole;
            }

            public function getName(): string
            {
                return 'role2';
            }

            public function getTitle(): string
            {
                return '角色2';
            }

            public function getPreconditionRole(): Role
            {
                return $this->preconditionRole;
            }
        };

        // 设置角色1的前置条件为角色2，形成循环引用
        $role1->setPreconditionRole($role2);

        // 断言循环引用结构 - 在实际应用中这种情况应该被系统检测并阻止
        $this->assertEquals('role1', $role1->getName());
        $this->assertEquals('role2', $role2->getName());

        $this->assertSame($role2, $role1->getPreconditionRole());
        $this->assertSame($role1, $role2->getPreconditionRole());

        // 验证可以检测到循环引用
        $visited = [];
        $current = $role1;
        $hasCircular = false;

        while ($current instanceof PreconditionRole && null !== $current->getPreconditionRole()) {
            $currentName = $current->getName();
            if (in_array($currentName, $visited, true)) {
                $hasCircular = true;
                break;
            }

            $visited[] = $currentName;
            $current = $current->getPreconditionRole();
        }

        $this->assertTrue($hasCircular, '应检测到循环引用前置条件');
    }
}
