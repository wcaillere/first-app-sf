<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
#[ORM\Table(name: 'authors')]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\NotBlank(message: 'Le prénom ne peut être vide')]
    #[Assert\Length(
        min: 3,
        max: 30,
        minMessage: 'Le prénom ne peut comporter moins de {{ limit }} caractères',
        maxMessage: 'Le prénom ne peut comporter plus de {{ limit }} caractères'
    )
    ]
    private ?string $firstName = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Le nom ne peut être vide')]
    #[Assert\Length(
        min: 3,
        max: 30,
        minMessage: 'Le nom ne peut comporter moins de {{ limit }} caractères',
        maxMessage: 'Le nom ne peut comporter plus de {{ limit }} caractères'
    )
    ]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(
        max: 144,
        maxMessage: 'La biographie ne peut comporter plus de {{ limit }} caractères'
    )
    ]
    private ?string $bio = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }
}
