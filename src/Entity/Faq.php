<?php

namespace App\Entity;

use App\Repository\FaqRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FaqRepository::class)]
class Faq
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subtitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    /**
     * @var Collection<int, FaqItem>
     */
    #[
        ORM\OneToMany(
            targetEntity: FaqItem::class,
            mappedBy: "faq",
            orphanRemoval: true,
            cascade: ["persist"],
        ),
    ]
    #[ORM\OrderBy(["position" => "ASC"])]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): static
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, FaqItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(FaqItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setFaq($this);
        }

        return $this;
    }

    public function removeItem(FaqItem $item): static
    {
        if ($this->items->removeElement($item) && $item->getFaq() === $this) {
            $item->setFaq(null);
        }

        return $this;
    }
}
