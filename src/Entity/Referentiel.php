<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * routePrefix="/admin",
 *      collectionOperations={
 *          "show_ref"={
 *                "method"="get",
 *                 "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 *          "show_gc_competences"={
 *              "method"="get",
 *               "path"="/referentiels/grpecompetences",
 *               "normalization_context"={"groups":"grpecompetence_only"},
 *               "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 *          "add_ref"={
 *              "method"="post",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 * },
 *      itemOperations={
 *            "show_one_ref"={
 *               "method"="get",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 *          "show_one_ref_grpecompetence"={
 *              "method"="get",
 *              "path"="/referentiels/{id}/grpecompetences",
 *              "normalization_context"={"groups":"grpecompetence_only"},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 *          "edit_one_ref"={
 *               "method"="put",
 *               "denormalization_context"={"groups"="editref:write"},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 * }, 
 * )
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 */
class Referentiel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grpecompetence_only","editref:write"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     * @Groups({"grpecompetence_only","editref:write"})
     */
    private $presentation;

    /**
     * @ORM\Column(type="text")
     * @Groups({"grpecompetence_only","editref:write"})
     */
    private $programme;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grpecompetence_only","editref:write"})
     */
    private $critereAdmision;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grpecompetence_only","editref:write"})
     */
    private $critereEvaluation;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("editref:write")
     */
    private $statut=false;

    /**
     * @ORM\OneToMany(targetEntity=Promos::class, mappedBy="referentiel")
     */
    private $promos;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeDeCompetence::class, inversedBy="referentiels")
     * @Groups({"grpecompetence_only","editref:write"})
     */
    private $groupeDeCompetence;

    public function __construct()
    {
        $this->promos = new ArrayCollection();
        $this->groupeDeCompetence = new ArrayCollection();
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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getProgramme(): ?string
    {
        return $this->programme;
    }

    public function setProgramme(string $programme): self
    {
        $this->programme = $programme;

        return $this;
    }

    public function getCritereAdmision(): ?string
    {
        return $this->critereAdmision;
    }

    public function setCritereAdmision(string $critereAdmision): self
    {
        $this->critereAdmision = $critereAdmision;

        return $this;
    }

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

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
     * @return Collection|Promos[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promos $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->setReferentiel($this);
        }

        return $this;
    }

    public function removePromo(Promos $promo): self
    {
        if ($this->promos->contains($promo)) {
            $this->promos->removeElement($promo);
            // set the owning side to null (unless already changed)
            if ($promo->getReferentiel() === $this) {
                $promo->setReferentiel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GroupeDeCompetence[]
     */
    public function getGroupeDeCompetence(): Collection
    {
        return $this->groupeDeCompetence;
    }

    public function addGroupeDeCompetence(GroupeDeCompetence $groupeDeCompetence): self
    {
        if (!$this->groupeDeCompetence->contains($groupeDeCompetence)) {
            $this->groupeDeCompetence[] = $groupeDeCompetence;
        }

        return $this;
    }

    public function removeGroupeDeCompetence(GroupeDeCompetence $groupeDeCompetence): self
    {
        if ($this->groupeDeCompetence->contains($groupeDeCompetence)) {
            $this->groupeDeCompetence->removeElement($groupeDeCompetence);
        }

        return $this;
    }
}
