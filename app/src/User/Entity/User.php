<?php

declare(strict_types=1);

namespace App\User\Entity;

use App\User\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherAwareInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="users"
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("username")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class User implements PasswordAuthenticatedUserInterface, PasswordHasherAwareInterface, UserInterface
{
    use SoftDeleteableEntity;

    public const ROLE_DEFAULT = 'ROLE_USER';

    public const ROLE_ACTIVE = 'ROLE_ACTIVE_USER';

    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    public const HASHING_ALGORITHM_ARGON2I = 'argon2i';

    public const HASHING_ALGORITHM_MD5 = 'md5';

    public const HASHING_ALGORITHM_BCRYPT = 'bcrypt';

    public const STATUS_NEW = 'new';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_DELETED = 'deleted';
    public const STATUS_BLOCKED = 'blocked';

    public const STATUSES
        = [
            self::STATUS_NEW     => 'New',
            self::STATUS_ACTIVE  => 'Active',
            self::STATUS_DELETED => 'Deleted',
            self::STATUS_BLOCKED => 'Blocked',
        ];

    private const VALID_HASHING_ALGORITHMS
        = [
            self::HASHING_ALGORITHM_MD5,
            self::HASHING_ALGORITHM_ARGON2I,
            self::HASHING_ALGORITHM_BCRYPT,
        ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     */
    private string $username = '';

    /**
     * @ORM\Column(name="email", type="string", length=180)
     * @Assert\NotBlank()
     */
    private string $email = '';

    /**
     * @ORM\Column(type="array")
     *
     * @var string[]
     */
    private array $roles = [];

    /**
     * Encrypted password. Must be persisted.
     *
     * @ORM\Column(type="string")
     */
    private string $password = '';

    /**
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $lastLogin = null;

    private ?string $plainPassword = null;

    /**
     * @ORM\Column(nullable=true)
     * @Assert\NotBlank()
     */
    private ?string $name;

    /**
     * @ORM\Column(name="registered_at", type="datetime")
     */
    private \DateTimeInterface $registeredAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @ORM\Column(name="hashing_algorithm", type="string", nullable=false,
     *     options={"default": User::HASHING_ALGORITHM_ARGON2I}
     * )
     */
    private string $hashingAlgorithm;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $phone = null;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private bool $confirmed = false;

    /**
     * @ORM\Column(type="string", options={"default"=self::STATUS_NEW})
     */
    private string $status = self::STATUS_NEW;

    public function __construct()
    {
        $this->hashingAlgorithm = self::HASHING_ALGORITHM_ARGON2I;
        $this->name = null;
        $this->registeredAt = new \DateTime();
    }

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->name, $this->username);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        // we need to make sure to have at least one role
        $roles[] = self::ROLE_DEFAULT;

        return array_unique($roles);
    }

    public function addRole(string $role): self
    {
        $role = strtoupper($role);
        if (self::ROLE_DEFAULT === $role) {
            return $this;
        }

        if (!\in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(string $role): self
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    public function hasRole(string $role): bool
    {
        return \in_array($role, $this->getRoles(), true);
    }

    public function isAdmin(): bool
    {
        return !empty(array_intersect($this->getRoles(), [self::ROLE_ADMIN, self::ROLE_SUPER_ADMIN]));
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): self
    {
        $this->plainPassword = null;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }


    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setRegisteredAt(\DateTimeInterface $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    public function getRegisteredAt(): \DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function setHashingAlgorithm(string $hashingAlgorithm): self
    {
        if (!$this->isValidHashingAlgorithm($hashingAlgorithm)) {
            throw new \LogicException(sprintf('"%s" - is invalid hashing algorithm', $hashingAlgorithm));
        }

        $this->hashingAlgorithm = $hashingAlgorithm;

        return $this;
    }

    public function isValidHashingAlgorithm(string $hashingAlgorithm): bool
    {
        if (\in_array($hashingAlgorithm, self::VALID_HASHING_ALGORITHMS, true)) {
            return true;
        }

        return false;
    }

    public function getPasswordHasherName(): ?string
    {
        if (!$this->isValidHashingAlgorithm($this->hashingAlgorithm)) {
            throw new \LogicException(sprintf('"%s" - is invalid hashing algorithm', $this->hashingAlgorithm));
        }

        return sprintf('%s_encoder', $this->hashingAlgorithm);
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeInterface $lastLogin): static
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    public function setConfirmed(bool $confirmed): static
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
