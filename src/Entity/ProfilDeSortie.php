<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProfilDeSortieRepository;
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
 *  routePrefix="/admin",
 *    collectionOperations={
 *      "addProfiDeSortie"={
 *          "method"="post",
 *          "denormalization_context"={"groups"="addProfiDeSortie:write"},
 *          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *           "security_message" = "Accès non autorisé",
 * },
 *      "show_profil_sortie" = {
 *          "method"="GET",
 *          "normalization_context"={"groups":"profilsortie:read"},
 *          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *           "security_message" = "Accès non autorisé",
 * },
 * },
 *    itemOperations={
 *          "getStudentsOfAPs"={
 *              "method"="get",
 *              "normalization_context"={"groups":"Oneprofilsortie:read"},
 *              "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "security_message" = "Accès non autorisé",
 * },
 *          "update_ps"={
 *              "method"="put",
 *              "denormalization_context"={"groups"="editProfiDeSortie:write"},
 *              "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "security_message" = "Accès non autorisé",
 * },
 *          "delete"={
 *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "security_message" = "Accès non autorisé",
 *          },
 * },
 * )
 * @ORM\Entity(repositoryClass=ProfilDeSortieRepository::class)
 */
class ProfilDeSortie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"editProfiDeSortie:write", "addProfiDeSortie:write" ,"profilsortie:read","Oneprofilsortie:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"editProfiDeSortie:write"})
     */
    private $statut=false;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="profilDeSortie")
     * @Groups({"Oneprofilsortie:read"})
     */
    private $Apprenant;

    public function __construct()
    {
        $this->Apprenant = new ArrayCollection();
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
     * @return Collection|Apprenant[]
     */
    public function getApprenant(): Collection
    {
        return $this->Apprenant;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->Apprenant->contains($apprenant)) {
            $this->Apprenant[] = $apprenant;
            $apprenant->setProfilDeSortie($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->Apprenant->contains($apprenant)) {
            $this->Apprenant->removeElement($apprenant);
            // set the owning side to null (unless already changed)
            if ($apprenant->getProfilDeSortie() === $this) {
                $apprenant->setProfilDeSortie(null);
            }
        }

        return $this;
    }

    
}
