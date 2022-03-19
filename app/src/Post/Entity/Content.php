<?php

declare(strict_types=1);

namespace App\Post\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Ramsey\Uuid\Uuid;

#[Orm\Entity]
#[Orm\Table(name: 'post_content')]
#[Index(columns: ['position'], name: 'IDX_Position')]
#[Index(columns: ['type'], name: 'IDX_Type')]
class Content
{
    protected const TYPE_TEXT = 'text';
    protected const TYPE_IMAGE = 'image';
    protected const TYPE_VIDEO = 'video';

    #[Orm\Column(type: Types::GUID)]
    #[Orm\Id]
    #[Orm\GeneratedValue(strategy: 'NONE')]
    protected string $id;

    #[Orm\ManyToOne(targetEntity: Post::class, inversedBy: 'content')]
    #[Orm\JoinColumn(name: 'post_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    protected Post $post;

    #[Orm\Column(name: 'type', type: 'string', nullable: false)]
    protected string $type;

    // protected $imageId; // TODO files

    #[Orm\Column(name: 'body', type: 'string', nullable: true)]
    protected ?string $body;

    #[Orm\Column(name: 'position', type: 'integer', nullable: false)]
    protected int $position = 1;

    public function __construct(Post $post, string $type)
    {
        $this->id = Uuid::uuid6()->toString();
        $this->post = $post;
        $this->type = $type;
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
}
