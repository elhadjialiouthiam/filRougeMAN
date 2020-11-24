<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeDeCompetenceRepository;
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
 * denormalizationContext={"groups":{"gc:write"}},
 *     collectionOperations={
 *          "show_gcs"={
 *                "method"="get",
 *                "path"="/admin/grpecompetences",
 *                 "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 *          "show_gc_competences"={
 *              "method"="get",
 *               "path"="/admin/grpecompetences/competences",
 *               "normalization_context"={"groups":"competence_only"},
 *              "access_control"="(is_granted('ROLE_ADMIN'))"
 * },
 *          "add_groupCompetences"={
 *              "method"="post",
 *              "path"="/admin/grpecompetences",
 *              "access_control"="(is_granted('ROLE_ADMIN'))"
 * },
 * },
 *     itemOperations={
 *            "show_one_gc"={
 *               "method"="get",
 *              "path"="/admin/grpecompetences/{id}",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 *          "show_oneGc_competence"={
 *              "method"="get",
 *              "path"="/admin/grpecompetences/{id}/competences",
 *              "normalization_context"={"groups":"competence_only"},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 *          "add_newCompetence"={
 *              "method"="PUT",
 *              "denormalization_context"={"groups":"addNc:write"},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 * },
 * )
 * @ORM\Entity(repositoryClass=GroupeDeCompetenceRepository::class)
 */
class GroupeDeCompetence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"gc:write","competence_only"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"gc:write","competence_only"})
     */
    private $descriptif;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="groupeDeCompetences")
     * @Groups({"gc:write","competence_only"})
     */
    private $competences;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
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
     * @return Collection|Competence[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        if ($this->competences->contains($competence)) {
            $this->competences->removeElement($competence);
        }

        return $this;
    }
}
