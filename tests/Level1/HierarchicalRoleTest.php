<?php

namespace Tourze\RBAC\Core\Tests\Level1;

use PHPUnit\Framework\TestCase;
use Tourze\RBAC\Core\Level0\Role;
use Tourze\RBAC\Core\Level1\HierarchicalRole;

class HierarchicalRoleTest extends TestCase
{
    /**
     * 测试 HierarchicalRole 接口的基本实现
     */
    public function testHierarchicalRole_basicImplementation(): void
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

        // 创建层级角色
        $hierarchicalRole = new class($userRole) implements Role, HierarchicalRole {
            private Role $parentRole;

            public function __construct(Role $parentRole)
            {
                $this->parentRole = $parentRole;
            }

            public function getName(): string
            {
                return 'editor';
            }

            public function getTitle(): string
            {
                return '编辑者';
            }

            public function getHierarchicalRoles(): array
            {
                return [$this->parentRole];
            }
        };

        // 断言层级角色属性
        $this->assertEquals('editor', $hierarchicalRole->getName());
        $this->assertEquals('编辑者', $hierarchicalRole->getTitle());
        $this->assertCount(1, $hierarchicalRole->getHierarchicalRoles());
        $this->assertSame($userRole, $hierarchicalRole->getHierarchicalRoles()[0]);
        $this->assertEquals('user', $hierarchicalRole->getHierarchicalRoles()[0]->getName());
    }

    /**
     * 测试多级继承角色
     */
    public function testHierarchicalRole_multiLevelInheritance(): void
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

        // 创建编辑者角色
        $editorRole = new class($userRole) implements Role, HierarchicalRole {
            private Role $parentRole;

            public function __construct(Role $parentRole)
            {
                $this->parentRole = $parentRole;
            }

            public function getName(): string
            {
                return 'editor';
            }

            public function getTitle(): string
            {
                return '编辑者';
            }

            public function getHierarchicalRoles(): array
            {
                return [$this->parentRole];
            }
        };

        // 创建管理员角色
        $adminRole = new class($editorRole) implements Role, HierarchicalRole {
            private Role $parentRole;

            public function __construct(Role $parentRole)
            {
                $this->parentRole = $parentRole;
            }

            public function getName(): string
            {
                return 'admin';
            }

            public function getTitle(): string
            {
                return '管理员';
            }

            public function getHierarchicalRoles(): array
            {
                return [$this->parentRole];
            }
        };

        // 断言多级继承角色
        $this->assertEquals('admin', $adminRole->getName());
        $this->assertEquals('管理员', $adminRole->getTitle());
        $this->assertCount(1, $adminRole->getHierarchicalRoles());

        $editorFromAdmin = $adminRole->getHierarchicalRoles()[0];
        $this->assertEquals('editor', $editorFromAdmin->getName());
        $this->assertInstanceOf(HierarchicalRole::class, $editorFromAdmin);

        $userFromEditor = $editorFromAdmin->getHierarchicalRoles()[0];
        $this->assertEquals('user', $userFromEditor->getName());
    }

    /**
     * 测试多个父角色
     */
    public function testHierarchicalRole_multipleParentRoles(): void
    {
        // 创建基础角色 - 内容创建者
        $contentCreatorRole = new class implements Role {
            public function getName(): string
            {
                return 'content_creator';
            }

            public function getTitle(): string
            {
                return '内容创建者';
            }
        };

        // 创建基础角色 - 内容审核者
        $contentReviewerRole = new class implements Role {
            public function getName(): string
            {
                return 'content_reviewer';
            }

            public function getTitle(): string
            {
                return '内容审核者';
            }
        };

        // 创建编辑者角色，继承多个父角色
        $editorRole = new class($contentCreatorRole, $contentReviewerRole) implements Role, HierarchicalRole {
            private array $parentRoles;

            public function __construct(Role $contentCreator, Role $contentReviewer)
            {
                $this->parentRoles = [$contentCreator, $contentReviewer];
            }

            public function getName(): string
            {
                return 'editor';
            }

            public function getTitle(): string
            {
                return '编辑者';
            }

            public function getHierarchicalRoles(): array
            {
                return $this->parentRoles;
            }
        };

        // 断言多父角色继承
        $this->assertEquals('editor', $editorRole->getName());
        $this->assertEquals('编辑者', $editorRole->getTitle());
        $this->assertCount(2, $editorRole->getHierarchicalRoles());
        $this->assertEquals('content_creator', $editorRole->getHierarchicalRoles()[0]->getName());
        $this->assertEquals('content_reviewer', $editorRole->getHierarchicalRoles()[1]->getName());
    }

    /**
     * 测试没有父角色的层级角色
     */
    public function testHierarchicalRole_withNoParentRoles(): void
    {
        // 创建没有父角色的层级角色
        $rootRole = new class implements Role, HierarchicalRole {
            public function getName(): string
            {
                return 'root';
            }

            public function getTitle(): string
            {
                return '根角色';
            }

            public function getHierarchicalRoles(): array
            {
                return [];
            }
        };

        // 断言无父角色的层级角色
        $this->assertEquals('root', $rootRole->getName());
        $this->assertEquals('根角色', $rootRole->getTitle());
        $this->assertEmpty($rootRole->getHierarchicalRoles());
    }
}
