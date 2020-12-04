<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ApiFilter(
 * BooleanFilter::class, properties={"statut"}
 * )
 * @ApiResource(
 * routePrefix="/admin",
 *      collectionOperations={
 *          "show_grpe"={
 *                "method"="get",
 *                 "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 *          "show_grpe_apprenant"={
 *              "method"="get",
 *               "path"="/groupes/apprenants",
 *               "normalization_context"={"groups":"apprenants_only"},
 *               "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 *          "add_grpe"={
 *              "method"="post",
 *              "denormalization_context"={"groups"="addgroupe:write"},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 * },
 *      itemOperations={
 *            "show_one_grpe"={
 *               "method"="get",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 *          "edit_one_grpe"={
 *               "method"="put",
 *               "denormalization_context"={"groups"="editgroupe:write"},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))"
 * },
 *          "removeApprenantFromGroup"={
 *              "route_name"="removeApprenantFromGroup", 
 *              "method"="delete",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))"
 * }
 * },
 * )
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 */
class Groupe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"apprenants_only","addgroupe:write","editgroupe:write"})
     */
    private $nom;

    /**
     * @ORM\Column(type="date")
     * @Groups({"apprenants_only","addgroupe:write"})
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"apprenants_only","addgroupe","editgroupe:write"})
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut=false;

    /**
     * @ORM\ManyToOne(targetEntity=Promos::class, inversedBy="groupes")
     */
    private $promos;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupes")
     * @Groups({"apprenants_only","addgroupe:write","editgroupe:write"})
     */
    private $apprenants;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupes")
     * @Groups({"addgroupe:write"})
     */
    private $formateurs;


    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getPromos(): ?Promos
    {
        return $this->promos;
    }

    public function setPromos(?Promos $promos): self
    {
        $this->promos = $promos;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->contains($apprenant)) {
            $this->apprenants->removeElement($apprenant);
        }

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        if ($this->formateurs->contains($formateur)) {
            $this->formateurs->removeElement($formateur);
        }

        return $this;
    }

}
