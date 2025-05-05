<?php

namespace Tourze\RBAC\Core\Tests\Level2;

use PHPUnit\Framework\TestCase;
use Tourze\RBAC\Core\Level0\Role;
use Tourze\RBAC\Core\Level2\MutexRole;

class MutexRoleTest extends TestCase
{
    /**
     * 测试 MutexRole 接口的基本实现
     */
    public function testMutexRole_basicImplementation(): void
    {
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

        // 创建会计角色，与审核员互斥
        $accountantRole = new class($reviewerRole) implements Role, MutexRole {
            private Role $mutexRole;

            public function __construct(Role $mutexRole)
            {
                $this->mutexRole = $mutexRole;
            }

            public function getName(): string
            {
                return 'accountant';
            }

            public function getTitle(): string
            {
                return '会计';
            }

            public function getMutexRoles(): array
            {
                return [$this->mutexRole];
            }
        };

        // 断言互斥角色关系
        $this->assertEquals('accountant', $accountantRole->getName());
        $this->assertEquals('会计', $accountantRole->getTitle());
        $this->assertCount(1, $accountantRole->getMutexRoles());
        $this->assertSame($reviewerRole, $accountantRole->getMutexRoles()[0]);
        $this->assertEquals('reviewer', $accountantRole->getMutexRoles()[0]->getName());
    }

    /**
     * 测试与多个角色互斥
     */
    public function testMutexRole_multipleExclusions(): void
    {
        // 创建审计师角色
        $auditorRole = new class implements Role {
            public function getName(): string
            {
                return 'auditor';
            }

            public function getTitle(): string
            {
                return '审计师';
            }
        };

        // 创建会计角色
        $accountantRole = new class implements Role {
            public function getName(): string
            {
                return 'accountant';
            }

            public function getTitle(): string
            {
                return '会计';
            }
        };

        // 创建出纳角色，与审计师和会计互斥
        $cashierRole = new class($auditorRole, $accountantRole) implements Role, MutexRole {
            private array $mutexRoles;

            public function __construct(Role $auditor, Role $accountant)
            {
                $this->mutexRoles = [$auditor, $accountant];
            }

            public function getName(): string
            {
                return 'cashier';
            }

            public function getTitle(): string
            {
                return '出纳';
            }

            public function getMutexRoles(): array
            {
                return $this->mutexRoles;
            }
        };

        // 断言多互斥角色关系
        $this->assertEquals('cashier', $cashierRole->getName());
        $this->assertEquals('出纳', $cashierRole->getTitle());
        $this->assertCount(2, $cashierRole->getMutexRoles());
        $this->assertEquals('auditor', $cashierRole->getMutexRoles()[0]->getName());
        $this->assertEquals('accountant', $cashierRole->getMutexRoles()[1]->getName());
    }

    /**
     * 测试双向互斥关系
     */
    public function testMutexRole_bidirectionalExclusion(): void
    {
        // 创建审计师角色，会与会计互斥
        $auditorRole = new class implements Role, MutexRole {
            private ?Role $accountantRole = null;

            public function getName(): string
            {
                return 'auditor';
            }

            public function getTitle(): string
            {
                return '审计师';
            }

            public function setMutexRole(Role $role): void
            {
                $this->accountantRole = $role;
            }

            public function getMutexRoles(): array
            {
                return $this->accountantRole ? [$this->accountantRole] : [];
            }
        };

        // 创建会计角色，会与审计师互斥
        $accountantRole = new class($auditorRole) implements Role, MutexRole {
            private Role $auditorRole;

            public function __construct(Role $auditor)
            {
                $this->auditorRole = $auditor;
                if ($this->auditorRole instanceof MutexRole) {
                    $this->auditorRole->setMutexRole($this);
                }
            }

            public function getName(): string
            {
                return 'accountant';
            }

            public function getTitle(): string
            {
                return '会计';
            }

            public function getMutexRoles(): array
            {
                return [$this->auditorRole];
            }
        };

        // 断言双向互斥关系
        $this->assertEquals('accountant', $accountantRole->getName());
        $this->assertEquals('auditor', $auditorRole->getName());

        $this->assertCount(1, $accountantRole->getMutexRoles());
        $this->assertSame($auditorRole, $accountantRole->getMutexRoles()[0]);

        $this->assertCount(1, $auditorRole->getMutexRoles());
        $this->assertSame($accountantRole, $auditorRole->getMutexRoles()[0]);
    }

    /**
     * 测试没有互斥角色
     */
    public function testMutexRole_withNoMutexRoles(): void
    {
        // 创建没有互斥角色的角色
        $independentRole = new class implements Role, MutexRole {
            public function getName(): string
            {
                return 'independent';
            }

            public function getTitle(): string
            {
                return '独立角色';
            }

            public function getMutexRoles(): array
            {
                return [];
            }
        };

        // 断言无互斥角色
        $this->assertEquals('independent', $independentRole->getName());
        $this->assertEquals('独立角色', $independentRole->getTitle());
        $this->assertEmpty($independentRole->getMutexRoles());
    }
}
