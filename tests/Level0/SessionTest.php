<?php

namespace Tourze\RBAC\Core\Tests\Level0;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\RBAC\Core\Level0\Role;
use Tourze\RBAC\Core\Level0\Session;
use Tourze\RBAC\Core\Level0\User;

/**
 * @internal
 */
#[CoversClass(Session::class)]
final class SessionTest extends TestCase
{
    /**
     * 测试 Session 接口的基本实现
     */
    public function testSessionBasicImplementation(): void
    {
        // 创建角色
        $adminRole = new class implements Role {
            public function getName(): string
            {
                return 'admin';
            }

            public function getTitle(): string
            {
                return '管理员';
            }
        };

        // 创建用户
        $user = new class implements User {
            public function getRoles(): array
            {
                return [];
            }

            public function getSessions(): array
            {
                return [];
            }
        };

        // 创建会话
        $session = new class(1, $user, [$adminRole]) implements Session {
            private int $id;

            private User $user;

            /** @var array<Role> */
            private array $roles;

            /** @param array<Role> $roles */
            public function __construct(int $id, User $user, array $roles)
            {
                $this->id = $id;
                $this->user = $user;
                $this->roles = $roles;
            }

            public function getId(): int
            {
                return $this->id;
            }

            public function getUser(): User
            {
                return $this->user;
            }

            public function getRoles(): array
            {
                return $this->roles;
            }
        };

        // 断言会话属性
        $this->assertEquals(1, $session->getId());
        $this->assertSame($user, $session->getUser());
        $this->assertCount(1, $session->getRoles());
        $this->assertEquals('admin', $session->getRoles()[0]->getName());
    }

    /**
     * 测试会话具有多个角色
     */
    public function testSessionWithMultipleRoles(): void
    {
        // 创建角色
        $adminRole = new class implements Role {
            public function getName(): string
            {
                return 'admin';
            }

            public function getTitle(): string
            {
                return '管理员';
            }
        };

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

        // 创建用户
        $user = new class implements User {
            public function getRoles(): array
            {
                return [];
            }

            public function getSessions(): array
            {
                return [];
            }
        };

        // 创建多角色会话
        $session = new class(1, $user, [$adminRole, $editorRole]) implements Session {
            private int $id;

            private User $user;

            /** @var array<Role> */
            private array $roles;

            /** @param array<Role> $roles */
            public function __construct(int $id, User $user, array $roles)
            {
                $this->id = $id;
                $this->user = $user;
                $this->roles = $roles;
            }

            public function getId(): int
            {
                return $this->id;
            }

            public function getUser(): User
            {
                return $this->user;
            }

            public function getRoles(): array
            {
                return $this->roles;
            }
        };

        // 断言多角色会话属性
        $this->assertEquals(1, $session->getId());
        $this->assertSame($user, $session->getUser());
        $this->assertCount(2, $session->getRoles());
        $this->assertEquals('admin', $session->getRoles()[0]->getName());
        $this->assertEquals('editor', $session->getRoles()[1]->getName());
    }

    /**
     * 测试空角色会话
     */
    public function testSessionWithNoRoles(): void
    {
        // 创建用户
        $user = new class implements User {
            public function getRoles(): array
            {
                return [];
            }

            public function getSessions(): array
            {
                return [];
            }
        };

        // 创建空角色会话
        $session = new class(1, $user, []) implements Session {
            private int $id;

            private User $user;

            /** @var array<Role> */
            private array $roles;

            /** @param array<Role> $roles */
            public function __construct(int $id, User $user, array $roles)
            {
                $this->id = $id;
                $this->user = $user;
                $this->roles = $roles;
            }

            public function getId(): int
            {
                return $this->id;
            }

            public function getUser(): User
            {
                return $this->user;
            }

            public function getRoles(): array
            {
                return $this->roles;
            }
        };

        // 断言空角色会话属性
        $this->assertEquals(1, $session->getId());
        $this->assertSame($user, $session->getUser());
        $this->assertEmpty($session->getRoles());
    }

    /**
     * 测试不同 ID 的会话
     */
    public function testSessionWithDifferentIds(): void
    {
        // 创建用户
        $user = new class implements User {
            public function getRoles(): array
            {
                return [];
            }

            public function getSessions(): array
            {
                return [];
            }
        };

        // 创建会话工厂
        $createSession = function (int $id) use ($user) {
            return new class($id, $user, []) implements Session {
                private int $id;

                private User $user;

                /** @var array<Role> */
                private array $roles;

                /** @param array<Role> $roles */
                public function __construct(int $id, User $user, array $roles)
                {
                    $this->id = $id;
                    $this->user = $user;
                    $this->roles = $roles;
                }

                public function getId(): int
                {
                    return $this->id;
                }

                public function getUser(): User
                {
                    return $this->user;
                }

                public function getRoles(): array
                {
                    return $this->roles;
                }
            };
        };

        // 创建多个会话
        $session1 = $createSession(1);
        $session2 = $createSession(2);
        $session3 = $createSession(3);

        // 断言不同 ID 的会话
        $this->assertEquals(1, $session1->getId());
        $this->assertEquals(2, $session2->getId());
        $this->assertEquals(3, $session3->getId());

        $this->assertNotEquals($session1->getId(), $session2->getId());
        $this->assertNotEquals($session2->getId(), $session3->getId());
        $this->assertNotEquals($session1->getId(), $session3->getId());
    }
}
