<?php

namespace App\Entity;

use App\Repository\DepartmentsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartmentsRepository::class)]
class Departments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $faculty_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdOn = null;

    #[ORM\Column]
    private ?int $createdBy = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedOn = null;

    #[ORM\Column]
    private ?int $updatedBy = null;

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

    public function getFacultyId(): ?int
    {
        return $this->faculty_id;
    }

    public function setFacultyId(int $faculty_id): self
    {
        $this->faculty_id = $faculty_id;

        return $this;
    }

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->createdOn;
    }

    public function setCreatedOn(\DateTimeInterface $createdOn): self
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    public function setCreatedBy(int $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedOn(): ?\DateTimeInterface
    {
        return $this->updatedOn;
    }

    public function setUpdatedOn(\DateTimeInterface $updatedOn): self
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    public function getUpdatedBy(): ?int
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(int $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }
}
