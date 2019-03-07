<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlogPostHistoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class BlogPostHistory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BlogPost", inversedBy="blogPostHistories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="json")
     */
    private $changes = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?BlogPost
    {
        return $this->parent;
    }

    public function setParent(?BlogPost $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getChanges(): ?array
    {
        return $this->changes;
    }

    public function setChanges(array $changes): self
    {
        $this->changes = $changes;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function updatedTimestamps(): void
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }
}
