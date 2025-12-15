<?php

namespace App\Entity;

use App\Repository\CreditCardRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CreditCardRepository::class)]
class CreditCard
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le numéro de carte est obligatoire')]
    #[Assert\Regex(pattern: '/^\d+$/', message: 'Le numéro ne doit contenir que des chiffres')]
    #[Assert\Length(
        min: 16, 
        max: 16, 
        exactMessage: 'Le numéro doit comporter exactement 16 chiffres'
    )]
    private ?string $number = null;

    #[ORM\Column(length: 5)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: "/^(0[1-9]|1[0-2])\/\d{2}$/", message: 'Format invalide (MM/YY)')]
    private ?string $expirationDate = null;

    #[ORM\Column(length: 3)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 3, exactMessage: 'Le CVV doit faire 3 chiffres')]
    private ?string $cvv = null;

    #[ORM\ManyToOne(inversedBy: 'creditCards')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = str_replace(' ', '', $number);

        return $this;
    }

    public function getExpirationDate(): ?string
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(string $expirationDate): static
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function getCvv(): ?string
    {
        return $this->cvv;
    }

    public function setCvv(string $cvv): static
    {
        $this->cvv = $cvv;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
