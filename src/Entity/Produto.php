<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProdutoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProdutoRepository::class)]
#[ApiResource]
class Produto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $denominacao = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $valor = null;

    #[ORM\Column]
    private ?bool $ativo = null;

    #[ORM\OneToMany(mappedBy: 'produto', targetEntity: Ticket::class)]
    private Collection $tickets;

    #[ORM\OneToMany(mappedBy: 'produto', targetEntity: Lote::class, orphanRemoval: true)]
    private Collection $lotes;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->lotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDenominacao(): ?string
    {
        return $this->denominacao;
    }

    public function setDenominacao(string $denominacao): static
    {
        $this->denominacao = $denominacao;

        return $this;
    }

    public function getValor(): ?string
    {
        return $this->valor;
    }

    public function setValor(string $valor): static
    {
        $this->valor = $valor;

        return $this;
    }

    public function isAtivo(): ?bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): static
    {
        $this->ativo = $ativo;

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): static
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setProduto($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): static
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getProduto() === $this) {
                $ticket->setProduto(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Lote>
     */
    public function getLotes(): Collection
    {
        return $this->lotes;
    }

    public function addLote(Lote $lote): static
    {
        if (!$this->lotes->contains($lote)) {
            $this->lotes->add($lote);
            $lote->setProduto($this);
        }

        return $this;
    }

    public function removeLote(Lote $lote): static
    {
        if ($this->lotes->removeElement($lote)) {
            // set the owning side to null (unless already changed)
            if ($lote->getProduto() === $this) {
                $lote->setProduto(null);
            }
        }

        return $this;
    }

public function __toString(): string
{
    return $this->denominacao;
}
}
