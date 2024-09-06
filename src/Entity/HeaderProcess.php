<?php

namespace App\Entity;

use App\Repository\HeaderProcessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HeaderProcessRepository::class)]
class HeaderProcess
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $file_name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $execution_date = null;

    /**
     * @var Collection<int, DetailProcess>
     */
    #[ORM\OneToMany(targetEntity: DetailProcess::class, mappedBy: 'header_process_id', cascade: ['persist', 'remove'])]
    private Collection $detailProcesses;

    public function __construct()
    {
        $this->detailProcesses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(string $file_name): static
    {
        $this->file_name = $file_name;

        return $this;
    }

    public function getExecutionDate(): ?\DateTimeInterface
    {
        return $this->execution_date;
    }

    public function setExecutionDate(\DateTimeInterface $execution_date): static
    {
        $this->execution_date = $execution_date;

        return $this;
    }

    /**
     * @return Collection<int, DetailProcess>
     */
    public function getDetailProcesses(): Collection
    {
        return $this->detailProcesses;
    }

    public function addDetailProcess(DetailProcess $detailProcess): static
    {
        if (!$this->detailProcesses->contains($detailProcess)) {
            $this->detailProcesses->add($detailProcess);
            $detailProcess->setHeaderProcessId($this);
        }

        return $this;
    }

    public function removeDetailProcess(DetailProcess $detailProcess): static
    {
        if ($this->detailProcesses->removeElement($detailProcess)) {
            // set the owning side to null (unless already changed)
            if ($detailProcess->getHeaderProcessId() === $this) {
                $detailProcess->setHeaderProcessId(null);
            }
        }

        return $this;
    }
}
