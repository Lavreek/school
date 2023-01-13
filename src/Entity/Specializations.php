<?php

namespace App\Entity;

use App\Repository\SpecializationsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpecializationsRepository::class)]
class Specializations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $departament_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDepartamentId(): ?int
    {
        return $this->departament_id;
    }

    public function setDepartamentId(int $departament_id): self
    {
        $this->departament_id = $departament_id;

        return $this;
    }
}
