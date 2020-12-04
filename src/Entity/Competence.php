<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompetenceRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity("libelle")
 * @ApiFilter(
 * BooleanFilter::class, properties={"statut"}
 * )
 * @ApiResource(
 *  denormalizationContext={"groups":{"competence:write"}},
 *  normalizationContext={"groups":{"competence:read"}},
 *      collectionOperations={
 *          "show_comptences"={
 *              "method" = "get",
 *              "path"="/admin/competences",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 *        "post"={
 *          "path"="/admin/competences",
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 *      }
 * },
 * itemOperations={
 *      "show_one_competence"={
 *          "method"="get",
 *          "path"="/admin/competences/{id}",
 *          "security"="is_granted('VIEW_COMP',object)",
 *          "security_message"="Vous n'avez pas access à cette Ressource",
 *          
 * },
 *       "update_competence"={
 *          "method"="put",
 *          "path"="/admin/competences/{id}",
 *          "security"="is_granted('EDIT_COMP',object)",
 *          "security_message"="Vous n'avez pas access à cette Ressource",
 *          
 * },
 * }
 * )
 * @ORM\Entity(repositoryClass=CompetenceRepository::class)
 */
class Competence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"competence:write","gc:write" ,"competence:read" ,"niveau:write", "competence_only"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"competence:write","gc:write", "competence:read" , "competence_only"})
     */
    private $descriptif;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut=false;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeDeCompetence::class, mappedBy="competences")
     */
    private $groupeDeCompetences;

    /**
     * @ORM\OneToMany(targetEntity=Niveau::class, mappedBy="competence")
     * @Groups({"competence:write","gc:write", "competence:read" ,"competence_only"})
     */
    private $niveaux;

    public function __construct()
    {
        $this->groupeDeCompetences = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection|GroupeDeCompetence[]
     */
    public function getGroupeDeCompetences(): Collection
    {
        return $this->groupeDeCompetences;
    }

    public function addGroupeDeCompetence(GroupeDeCompetence $groupeDeCompetence): self
    {
        if (!$this->groupeDeCompetences->contains($groupeDeCompetence)) {
            $this->groupeDeCompetences[] = $groupeDeCompetence;
            $groupeDeCompetence->addCompetence($this);
        }

        return $this;
    }

    public function removeGroupeDeCompetence(GroupeDeCompetence $groupeDeCompetence): self
    {
        if ($this->groupeDeCompetences->contains($groupeDeCompetence)) {
            $this->groupeDeCompetences->removeElement($groupeDeCompetence);
            $groupeDeCompetence->removeCompetence($this);
        }

        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux[] = $niveau;
            $niveau->setCompetence($this);
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveaux->contains($niveau)) {
            $this->niveaux->removeElement($niveau);
            // set the owning side to null (unless already changed)
            if ($niveau->getCompetence() === $this) {
                $niveau->setCompetence(null);
            }
        }

        return $this;
    }
}
