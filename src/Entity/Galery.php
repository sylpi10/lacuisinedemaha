<?php

namespace App\Entity;

use App\Repository\GaleryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GaleryRepository::class)]
class Galery
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $homepageName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $homepageTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $galleryName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $galleryTitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, GaleryImage>
     */
    #[
        ORM\OneToMany(
            targetEntity: GaleryImage::class,
            mappedBy: "galery",
            orphanRemoval: true,
            cascade: ["persist"],
        ),
    ]
    #[ORM\OrderBy(["position" => "ASC"])]
    private Collection $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHomepageName(): ?string
    {
        return $this->homepageName;
    }

    public function setHomepageName(?string $homepageName): static
    {
        $this->homepageName = $homepageName;

        return $this;
    }

    public function getHomepageTitle(): ?string
    {
        return $this->homepageTitle;
    }

    public function setHomepageTitle(?string $homepageTitle): static
    {
        $this->homepageTitle = $homepageTitle;

        return $this;
    }

    public function getGalleryName(): ?string
    {
        return $this->galleryName;
    }

    public function setGalleryName(?string $galleryName): static
    {
        $this->galleryName = $galleryName;

        return $this;
    }

    public function getGalleryTitle(): ?string
    {
        return $this->galleryTitle;
    }

    public function setGalleryTitle(?string $galleryTitle): static
    {
        $this->galleryTitle = $galleryTitle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, GaleryImage>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(GaleryImage $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setGalery($this);
        }

        return $this;
    }

    public function removeImage(GaleryImage $image): static
    {
        if ($this->images->removeElement($image)) {
            if ($image->getGalery() === $this) {
                $image->setGalery(null);
            }
        }

        return $this;
    }
}
