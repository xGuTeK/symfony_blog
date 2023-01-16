<?php

namespace App\Entity;

use App\Repository\PostCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostCategoryRepository::class)]
class PostCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $visibility = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'postCategories')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $postCategories;

    public function __construct()
    {
        $this->postCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getPostCategories(): Collection
    {
        return $this->postCategories;
    }

    public function addPostCategory(self $postCategory): self
    {
        if (!$this->postCategories->contains($postCategory)) {
            $this->postCategories->add($postCategory);
            $postCategory->setParent($this);
        }

        return $this;
    }

    public function removePostCategory(self $postCategory): self
    {
        if ($this->postCategories->removeElement($postCategory)) {
            // set the owning side to null (unless already changed)
            if ($postCategory->getParent() === $this) {
                $postCategory->setParent(null);
            }
        }

        return $this;
    }
}
