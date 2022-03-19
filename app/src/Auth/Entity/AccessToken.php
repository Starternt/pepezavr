<?php

declare(strict_types=1);

namespace App\Auth\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Auth\DTO\CreateTokenDTO;
use App\User\Entity\User;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity()
 * @ORM\Table(name="auth_access_token")
 *
 * @ApiResource(
 *     collectionOperations={
 *      "get"={
 *          "read"=false,
 *          "output"=false,
 *      },
 *      "post"={
 *          "normalization_context"={"groups"={"auth:access_token"}}
 *      },
 *     },
 *     itemOperations={
 *      "get"={
 *          "read"=false,
 *          "output"=false,
 *      },
 *      "delete"={
 *          "security"="is_granted('auth.delete_token', object)",
 *      },
 *     },
 *     input=CreateTokenDTO::class,
 * )
 */
class AccessToken
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     *
     * @SerializedName("accessToken")
     * @Groups({"auth:access_token"})
     */
    private ?string $id = null;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"auth:access_token"})
     */
    private \DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"auth:access_token"})
     */
    private ?\DateTimeInterface $expiredAt = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getExpiredAt(): ?\DateTimeInterface
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(?\DateTimeInterface $expiredAt): static
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return $this
     */
    public function setExpiredAfter(int $seconds): static
    {
        /** @var \DateTimeInterface $expiredAt */
        $expiredAt = Carbon::createFromInterface($this->createdAt)->modify(sprintf('+ %d seconds', $seconds));

        return $this->setExpiredAt($expiredAt);
    }

    public function isActive(): bool
    {
        if (null === $this->expiredAt) {
            return true;
        }

        return $this->expiredAt > new \DateTimeImmutable();
    }
}
