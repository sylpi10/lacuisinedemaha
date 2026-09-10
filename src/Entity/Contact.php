<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $senderName = null;

    #[ORM\Column(length: 255)]
    private ?string $senderEmail = null;

    #[ORM\Column(length: 100)]
    private ?string $senderPhone = null;

    #[ORM\ManyToOne]
    private ?Formulas $wishedFormula = null;

    #[ORM\Column]
    private ?int $senderWishedNumber = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $senderDate = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $senderMessage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSenderName(): ?string
    {
        return $this->senderName;
    }

    public function setSenderName(string $senderName): static
    {
        $this->senderName = $senderName;

        return $this;
    }

    public function getSenderEmail(): ?string
    {
        return $this->senderEmail;
    }

    public function setSenderEmail(string $senderEmail): static
    {
        $this->senderEmail = $senderEmail;

        return $this;
    }

    public function getSenderPhone(): ?string
    {
        return $this->senderPhone;
    }

    public function setSenderPhone(string $senderPhone): static
    {
        $this->senderPhone = $senderPhone;

        return $this;
    }

    public function getWishedFormula(): ?Formulas
    {
        return $this->wishedFormula;
    }

    public function setWishedFormula(?Formulas $wishedFormula): static
    {
        $this->wishedFormula = $wishedFormula;

        return $this;
    }

    public function getSenderWishedNumber(): ?int
    {
        return $this->senderWishedNumber;
    }

    public function setSenderWishedNumber(int $senderWishedNumber): static
    {
        $this->senderWishedNumber = $senderWishedNumber;

        return $this;
    }

    public function getSenderDate(): ?\DateTime
    {
        return $this->senderDate;
    }

    public function setSenderDate(\DateTime $senderDate): static
    {
        $this->senderDate = $senderDate;

        return $this;
    }

    public function getSenderMessage(): ?string
    {
        return $this->senderMessage;
    }

    public function setSenderMessage(string $senderMessage): static
    {
        $this->senderMessage = $senderMessage;

        return $this;
    }
}
