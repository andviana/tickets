<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TicketRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ApiResource]
class Ticket
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ORM\Column]
    private ?int $numero = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dataEmissao = null;

    #[ORM\Column]
    private ?bool $recolhido = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produto $produto = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Evento $evento = null;

    #[ORM\OneToOne(mappedBy: 'ticket', cascade: ['persist', 'remove'])]
    private ?Registro $registro = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userEmissao = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    private ?Lote $lote = null;

    #[ORM\Column(nullable: true)]
    private ?int $sequenciaLote = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getDataEmissao(): ?\DateTimeInterface
    {
        return $this->dataEmissao;
    }

    public function setDataEmissao(\DateTimeInterface $dataEmissao): static
    {
        $this->dataEmissao = $dataEmissao;

        return $this;
    }

    public function isRecolhido(): ?bool
    {
        return $this->recolhido;
    }

    public function setRecolhido(bool $recolhido): static
    {
        $this->recolhido = $recolhido;

        return $this;
    }

    public function getProduto(): ?Produto
    {
        return $this->produto;
    }

    public function setProduto(?Produto $produto): static
    {
        $this->produto = $produto;

        return $this;
    }

    public function getEvento(): ?Evento
    {
        return $this->evento;
    }

    public function setEvento(?Evento $evento): static
    {
        $this->evento = $evento;

        return $this;
    }

    public function getRegistro(): ?Registro
    {
        return $this->registro;
    }

    public function setRegistro(Registro $registro): static
    {
        // set the owning side of the relation if necessary
        if ($registro->getTicket() !== $this) {
            $registro->setTicket($this);
        }

        $this->registro = $registro;

        return $this;
    }

    public function getUserEmissao(): ?User
    {
        return $this->userEmissao;
    }

    public function setUserEmissao(?User $userEmissao): static
    {
        $this->userEmissao = $userEmissao;

        return $this;
    }

    public function getLote(): ?Lote
    {
        return $this->lote;
    }

    public function setLote(?Lote $lote): static
    {
        $this->lote = $lote;

        return $this;
    }

    public function __toString(): string
    {
        return $this->produto;
    }

    public function getSequenciaLote(): ?int
    {
        return $this->sequenciaLote;
    }

    public function setSequenciaLote(?int $sequenciaLote): static
    {
        $this->sequenciaLote = $sequenciaLote;

        return $this;
    }
}
