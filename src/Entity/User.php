<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $genre = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Pokemon::class)]
    private Collection $pokemon;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $last_game = null;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Trade::class)]
    private Collection $trades_send;

    #[ORM\OneToMany(mappedBy: 'receiver', targetEntity: Trade::class)]
    private Collection $trades_receive;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Pokedex::class, orphanRemoval: true)]
    private Collection $pokedexes;

    public function __construct()
    {
        $this->pokemon = new ArrayCollection();
        $this->trades_send = new ArrayCollection();
        $this->trades_receive = new ArrayCollection();
        $this->pokedexes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getGenre(): ?int
    {
        return $this->genre;
    }

    public function setGenre(?int $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * @return Collection<int, Pokemon>
     */
    public function getPokemon(): Collection
    {
        return $this->pokemon;
    }

    public function addPokemon(Pokemon $pokemon): self
    {
        if (!$this->pokemon->contains($pokemon)) {
            $this->pokemon->add($pokemon);
            $pokemon->setUser($this);
        }

        return $this;
    }

    public function removePokemon(Pokemon $pokemon): self
    {
        if ($this->pokemon->removeElement($pokemon)) {
            // set the owning side to null (unless already changed)
            if ($pokemon->getUser() === $this) {
                $pokemon->setUser(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->username;
    }

    public function getLastGame(): ?\DateTimeInterface
    {
        return $this->last_game;
    }

    public function setLastGame(?\DateTimeInterface $last_game): self
    {
        $this->last_game = $last_game;

        return $this;
    }

    /**
     * @return Collection<int, Trade>
     */
    public function getTradesSend(): Collection
    {
        return $this->trades_send;
    }

    public function addTradesSend(Trade $tradesSend): self
    {
        if (!$this->trades_send->contains($tradesSend)) {
            $this->trades_send->add($tradesSend);
            $tradesSend->setSender($this);
        }

        return $this;
    }

    public function removeTradesSend(Trade $tradesSend): self
    {
        if ($this->trades_send->removeElement($tradesSend)) {
            // set the owning side to null (unless already changed)
            if ($tradesSend->getSender() === $this) {
                $tradesSend->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Trade>
     */
    public function getTradesReceive(): Collection
    {
        return $this->trades_receive;
    }

    public function addTradesReceive(Trade $tradesReceive): self
    {
        if (!$this->trades_receive->contains($tradesReceive)) {
            $this->trades_receive->add($tradesReceive);
            $tradesReceive->setReceiver($this);
        }

        return $this;
    }

    public function removeTradesReceive(Trade $tradesReceive): self
    {
        if ($this->trades_receive->removeElement($tradesReceive)) {
            // set the owning side to null (unless already changed)
            if ($tradesReceive->getReceiver() === $this) {
                $tradesReceive->setReceiver(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Pokedex>
     */
    public function getPokedexes(): Collection
    {
        return $this->pokedexes;
    }

    public function addPokedex(Pokedex $pokedex): self
    {
        if (!$this->pokedexes->contains($pokedex)) {
            $this->pokedexes->add($pokedex);
            $pokedex->setUser($this);
        }

        return $this;
    }

    public function removePokedex(Pokedex $pokedex): self
    {
        if ($this->pokedexes->removeElement($pokedex)) {
            // set the owning side to null (unless already changed)
            if ($pokedex->getUser() === $this) {
                $pokedex->setUser(null);
            }
        }

        return $this;
    }
}
