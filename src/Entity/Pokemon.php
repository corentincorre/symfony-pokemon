<?php

namespace App\Entity;

use App\Repository\PokemonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokemonRepository::class)]
class Pokemon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $pokemon_name = null;

    #[ORM\ManyToOne(inversedBy: 'pokemon')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $pokemon_image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPokemonName(): ?string
    {
        return $this->pokemon_name;
    }

    public function setPokemonName(string $pokemon_name): self
    {
        $this->pokemon_name = $pokemon_name;

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

    public function getPokemonImage(): ?string
    {
        return $this->pokemon_image;
    }

    public function setPokemonImage(string $pokemon_image): self
    {
        $this->pokemon_image = $pokemon_image;

        return $this;
    }
    public function __toString(): string
    {
        return $this->pokemon_name;
    }
}
