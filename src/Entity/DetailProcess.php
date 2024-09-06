<?php

namespace App\Entity;

use App\Repository\DetailProcessRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailProcessRepository::class)]
class DetailProcess
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private array $data = [];

    #[ORM\ManyToOne(inversedBy: 'detailProcesses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?HeaderProcess $header_process = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getHeaderProcessId(): ?HeaderProcess
    {
        return $this->header_process;
    }

    public function setHeaderProcessId(?HeaderProcess $header_process): static
    {
        $this->header_process = $header_process;

        return $this;
    }
}
