<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RegistroRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: RegistroRepository::class)]
#[ApiResource]
class Registro
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ORM\OneToOne(inversedBy: 'registro', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ticket $ticket = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dataRegistro = null;

    #[ORM\ManyToOne(inversedBy: 'registros')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userRegistro = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(Ticket $ticket): static
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function getDataRegistro(): ?\DateTimeInterface
    {
        return $this->dataRegistro;
    }

    public function setDataRegistro(\DateTimeInterface $dataRegistro): static
    {
        $this->dataRegistro = $dataRegistro;

        return $this;
    }

    public function getUserRegistro(): ?User
    {
        return $this->userRegistro;
    }

    public function setUserRegistro(?User $userRegistro): static
    {
        $this->userRegistro = $userRegistro;

        return $this;
    }
}
