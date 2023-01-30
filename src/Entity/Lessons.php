<?php

namespace App\Entity;

use App\Repository\LessonsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LessonsRepository::class)]
class Lessons
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $school_subject_id = null;

    #[ORM\Column]
    private ?int $program_id = null;

    #[ORM\Column]
    private ?int $teacher_id = null;

    #[ORM\Column(length: 255)]
    private ?string $day_of_week = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSchoolSubjectId(): ?int
    {
        return $this->school_subject_id;
    }

    public function setSchoolSubjectId(int $school_subject_id): self
    {
        $this->school_subject_id = $school_subject_id;

        return $this;
    }

    public function getProgramId(): ?int
    {
        return $this->program_id;
    }

    public function setProgramId(int $program_id): self
    {
        $this->program_id = $program_id;

        return $this;
    }

    public function getTeacherId(): ?int
    {
        return $this->teacher_id;
    }

    public function setTeacherId(int $teacher_id): self
    {
        $this->teacher_id = $teacher_id;

        return $this;
    }

    public function getDayOfWeek(): ?string
    {
        return $this->day_of_week;
    }

    public function setDayOfWeek(string $day_of_week): self
    {
        $this->day_of_week = $day_of_week;

        return $this;
    }
}
