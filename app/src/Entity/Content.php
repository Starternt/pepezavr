<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

#[Orm\Entity]
#[Orm\Table(name: 'post_content')]
#[Index(columns: ['position'], name: 'IDX_Position')]
#[Index(columns: ['type'], name: 'IDX_Type')]
class Content
{
    public const BODY_LENGTH = 15000;

    protected const TYPE_TEXT = 'text';
    protected const TYPE_IMAGE = 'image';

    #[ORM\ManyToOne(targetEntity: MediaObject::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[ApiProperty(iri: 'http://schema.org/image')]
    #[Groups(Post::FULL_GROUPS)]
    public ?MediaObject $image = null;

    #[Orm\Column(type: Types::GUID)]
    #[Orm\Id]
    #[Orm\GeneratedValue(strategy: 'NONE')]
    protected string $id;

    #[Orm\ManyToOne(targetEntity: Post::class, inversedBy: 'content')]
    #[Orm\JoinColumn(name: 'post_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    protected Post $post;

    #[Orm\Column(name: 'type', type: 'string', nullable: false)]
    #[Groups(Post::FULL_GROUPS)]
    #[NotBlank]
    #[Choice(choices: [self::TYPE_TEXT, self::TYPE_IMAGE])]
    protected ?string $type = null;

    #[Orm\Column(name: 'body', type: 'string', length: self::BODY_LENGTH, nullable: true)]
    #[Groups(Post::FULL_GROUPS)]
    #[NotBlank]
    protected ?string $body = null;

    #[Orm\Column(name: 'position', type: 'integer', nullable: false)]
    #[Groups(Post::FULL_GROUPS)]
    protected int $position = 1;

    public function __construct()
    {
        $this->id = Uuid::uuid6()->toString();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getImage(): ?MediaObject
    {
        return $this->image;
    }

    public function setImage(?MediaObject $image): self
    {
        $this->image = $image;

        return $this;
    }
}
