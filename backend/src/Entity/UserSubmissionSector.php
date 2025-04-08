<?php


namespace App\Entity;

use App\Repository\UserSubmissionSectorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserSubmissionSectorRepository::class)]
class UserSubmissionSector
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: UserSubmission::class, inversedBy: 'userSubmissionSectors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserSubmission $userSubmission = null;

    #[ORM\ManyToOne(targetEntity: Sector::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sector $sector = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserSubmission(): ?UserSubmission
    {
        return $this->userSubmission;
    }

    public function setUserSubmission(?UserSubmission $userSubmission): static
    {
        $this->userSubmission = $userSubmission;

        return $this;
    }

    public function getSector(): ?Sector
    {
        return $this->sector;
    }

    public function setSector(?Sector $sector): static
    {
        $this->sector = $sector;

        return $this;
    }
}
