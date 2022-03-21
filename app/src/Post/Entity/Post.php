<?php

declare(strict_types=1);

namespace App\Post\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Post\Api\Controller\CreatePostController;
use App\Post\Repository\PostRepository;
use App\User\Entity\User;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as Orm;
use Doctrine\ORM\Mapping\Index;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

#[Orm\Entity(repositoryClass: PostRepository::class)]
#[Orm\Table(name: 'posts')]
#[Index(columns: ['created_at'], name: 'IDX_Created_At')]
#[Index(columns: ['rating'], name: 'IDX_Rating')]
#[Index(columns: ['title'], name: 'IDX_Title')]
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => [
            'controller' => CreatePostController::class,
            'denormalization_context' => [
                'groups' => [
                    self::GROUP_WRITE,
                ],
            ],
        ],
    ],
    itemOperations: [
        'get',
    ],
    denormalizationContext: ['groups' => [self::GROUP_WRITE]],
    normalizationContext: ['groups' => [self::GROUP_READ]],
)]
class Post
{
    public const GROUP_READ = 'post:read';
    public const GROUP_WRITE = 'post:write';
    public const GROUP_UPDATE = 'post:update';

    public const FULL_GROUPS = [
        self::GROUP_READ,
        self::GROUP_WRITE,
        self::GROUP_UPDATE,
    ];

    #[Orm\Column(type: Types::GUID)]
    #[Orm\Id]
    #[Orm\GeneratedValue(strategy: 'NONE')]
    #[Groups([self::GROUP_READ])]
    protected string $id;

    #[Orm\Column(type: Types::STRING, length: 500, nullable: false)]
    #[Groups(self::FULL_GROUPS)]
    #[NotBlank]
    #[Length(max: 500)]
    protected string $title;

    #[Orm\ManyToOne(targetEntity: User::class)]
    #[Orm\JoinColumn(name: 'created_by', referencedColumnName: 'id', nullable: false)]
    protected User $createdBy;

    #[Orm\Column(type: Types::INTEGER, nullable: false, options: [
        'default' => 0,
    ])]
    #[Groups([self::GROUP_READ])]
    protected int $rating = 0;

    /**
     * @Gedmo\Timestampable(on="create")
     */
    #[Orm\Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE)]
    #[Groups([self::GROUP_READ])]
    protected DateTimeInterface $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     */
    #[Orm\Column(name: 'updated_at', type: Types::DATETIME_IMMUTABLE)]
    #[Groups([self::GROUP_READ])]
    protected ?DateTimeInterface $updatedAt;

    #[Orm\OneToMany(
        mappedBy: 'post',
        targetEntity: Content::class,
        cascade: ['persist'],
        orphanRemoval: true
    )
    ]
    #[Groups(self::FULL_GROUPS)]
    #[Count(min: 1, max: 10)]
    protected Collection $content;

    public function __construct(string $title)
    {
        $this->id = Uuid::uuid6()->toString();
        $this->title = $title;
        $this->createdAt = new \DateTimeImmutable();
        $this->content = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable|DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getContent(): Collection
    {
        return $this->content;
    }

    public function setContent($content): self
    {
        dump($content); exit();
        $this->content = $content;

        return $this;
    }
}
