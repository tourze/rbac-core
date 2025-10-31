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
#[CoversClass(User::class)]
final class UserTest extends TestCase
{
    /**
     * 测试 User 接口的基本实现
     */
    public function testUserBasicImplementation(): void
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

        // 创建 User 实现
        $user = new class($adminRole) implements User {
            /** @var array<Role> */
            private array $roles;

            /** @var array<Session> */
            private array $sessions;

            public function __construct(Role $role)
            {
                $this->roles = [$role];
                $this->sessions = [];
            }

            public function getRoles(): array
            {
                return $this->roles;
            }

            public function getSessions(): array
            {
                return $this->sessions;
            }
        };

        // 断言用户基本属性
        $this->assertCount(1, $user->getRoles());
        $this->assertEquals('admin', $user->getRoles()[0]->getName());
        $this->assertEmpty($user->getSessions());
    }

    /**
     * 测试用户拥有多个角色
     */
    public function testUserWithMultipleRoles(): void
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

        // 创建 User 实现
        $user = new class([$adminRole, $editorRole]) implements User {
            /** @var array<Role> */
            private array $roles;

            /** @var array<Session> */
            private array $sessions;

            /** @param array<Role> $roles */
            public function __construct(array $roles)
            {
                $this->roles = $roles;
                $this->sessions = [];
            }

            public function getRoles(): array
            {
                return $this->roles;
            }

            public function getSessions(): array
            {
                return $this->sessions;
            }
        };

        // 断言用户拥有多个角色
        $this->assertCount(2, $user->getRoles());
        $this->assertEquals('admin', $user->getRoles()[0]->getName());
        $this->assertEquals('editor', $user->getRoles()[1]->getName());
    }

    /**
     * 测试用户拥有会话
     */
    public function testUserWithSessions(): void
    {
        $self = $this;

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
        $session1 = new class(1, $user, [$adminRole]) implements Session {
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

        // 创建有会话的用户
        $userWithSession = new class($user, [$session1]) implements User {
            private User $userDelegate;

            /** @var array<Session> */
            private array $sessions;

            /** @param array<Session> $sessions */
            public function __construct(User $userDelegate, array $sessions)
            {
                $this->userDelegate = $userDelegate;
                $this->sessions = $sessions;
            }

            public function getRoles(): array
            {
                return $this->userDelegate->getRoles();
            }

            public function getSessions(): array
            {
                return $this->sessions;
            }
        };

        // 断言用户会话
        $this->assertCount(1, $userWithSession->getSessions());
        $this->assertEquals(1, $userWithSession->getSessions()[0]->getId());
        $this->assertSame($user, $userWithSession->getSessions()[0]->getUser());
    }

    /**
     * 测试用户没有角色
     */
    public function testUserWithNoRoles(): void
    {
        // 创建无角色用户
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

        // 断言用户没有角色
        $this->assertEmpty($user->getRoles());
        $this->assertCount(0, $user->getRoles());
    }
}
