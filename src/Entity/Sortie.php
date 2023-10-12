<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use App\Validator\Constraints\LessThanDateHeureDebut;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank(message="Veuillez renseigner un nom.")
     * @Assert\Length(max={150}, maxMessage="Le nom ne doit pas dépasser 150 caractères.")
     */
    private ?string $nom;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Veuillez renseigner une date et une heure.")
     * @Assert\GreaterThanOrEqual("now", message="La date et l'heure doivent être au minimum maintenant.")
     */
    private ?\DateTimeInterface $dateHeureDebut;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Veuillez renseigner une durée.")
     */
    private ?int $duree;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="Veuillez renseigner une date limite d\'inscription.")
     * @Assert\GreaterThanOrEqual("today", message="La date limite d'inscription doit être au minimum aujourd'hui.")
     * @LessThanDateHeureDebut
     */
    private ?\DateTimeInterface $dateLimiteInscription;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Veuillez renseigner un nombre de places.")
     */
    private ?int $nbInscriptionsMax;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Veuillez renseigner une description.")
     */
    private ?string $infosSortie;

    /**
     * @ORM\ManyToMany(targetEntity=Participant::class, mappedBy="sortiesParticipees")
     */
    private ?Collection $participants;

    /**
     * @ORM\ManyToOne(targetEntity=Participant::class, inversedBy="sortiesOrganisees")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Participant $participantOrganisateur = null;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Etat $etat = null;

    /**
     * @ORM\ManyToOne(targetEntity=Lieu::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Veuillez renseigner un lieu.")
     */
    private $lieu;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Veuillez renseigner un campus.")
     */
    private $campus;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(?\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(?\DateTimeInterface $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getNbInscriptionsMax(): ?int
    {
        return $this->nbInscriptionsMax;
    }

    public function setNbInscriptionsMax(?int $nbInscriptionsMax): self
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    public function setInfosSortie(?string $infosSortie): self
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(?Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->addSortieParticipee($this);
        }

        return $this;
    }

    public function removeParticipant(?Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            $participant->removeSortieParticipee($this);
        }

        return $this;
    }

    public function getParticipantOrganisateur(): ?Participant
    {
        return $this->participantOrganisateur;
    }

    public function setParticipantOrganisateur(?Participant $participant): self
    {
        if ($this->participantOrganisateur !== $participant) {
            if ($participant !== null) {
                $this->participantOrganisateur = $participant;
                $participant->addSortieOrganisee($this);
            } else {
                $participantWhereRemoveSortieOrganisee = $this->participantOrganisateur;
                $this->participantOrganisateur = $participant;
                $participantWhereRemoveSortieOrganisee->removeSortieOrganisee($this);
            }
        }

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        if ($this->etat !== $etat) {
            if ($etat !== null) {
                $this->etat = $etat;
                $etat->addSortie($this);
            } else {
                $etatWhereRemoveSortie = $this->etat;
                $this->etat = $etat;
                $etatWhereRemoveSortie->removeSortie($this);
            }
        }

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        if ($this->lieu !== $lieu) {
            if ($lieu !== null) {
                $this->lieu = $lieu;
                $lieu->addSortie($this);
            } else {
                $lieuWhereRemoveSortie = $this->lieu;
                $this->lieu = $lieu;
                $lieuWhereRemoveSortie->removeSortie($this);
            }
        }

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        if ($this->campus !== $campus) {
            if ($campus !== null) {
                $this->campus = $campus;
                $campus->addSortie($this);
            } else {
                $campusWhereRemoveSortie = $this->campus;
                $this->campus = $campus;
                $campusWhereRemoveSortie->removeSortie($this);
            }
        }
        return $this;
    }
}
