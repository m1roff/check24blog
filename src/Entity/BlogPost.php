<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlogPostRepository")
 */
class BlogPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $post_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="blogPosts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="boolean", options={"default" : false}))
     */
    private $is_deleted = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BlogPostHistory", mappedBy="parent")
     */
    private $blogPostHistories;

    public function __construct()
    {
        $this->blogPostHistories = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostDate(): ?\DateTimeInterface
    {
        return $this->post_date;
    }

    public function setPostDate(\DateTimeInterface $post_date): self
    {
        $this->post_date = $post_date;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->is_deleted;
    }

    public function setIsDeleted(bool $is_deleted): self
    {
        $this->is_deleted = $is_deleted;

        return $this;
    }

    /**
     * @return Collection|BlogPostHistory[]
     */
    public function getBlogPostHistories(): Collection
    {
        return $this->blogPostHistories;
    }

    public function addBlogPostHistory(BlogPostHistory $blogPostHistory): self
    {
        if (!$this->blogPostHistories->contains($blogPostHistory)) {
            $this->blogPostHistories[] = $blogPostHistory;
            $blogPostHistory->setParent($this);
        }

        return $this;
    }

    public function removeBlogPostHistory(BlogPostHistory $blogPostHistory): self
    {
        if ($this->blogPostHistories->contains($blogPostHistory)) {
            $this->blogPostHistories->removeElement($blogPostHistory);
            // set the owning side to null (unless already changed)
            if ($blogPostHistory->getParent() === $this) {
                $blogPostHistory->setParent(null);
            }
        }

        return $this;
    }
}
