<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 * @UniqueEntity("libelle")
 *@ApiFilter(
 * BooleanFilter::class, properties={"statut"}
 * )
 * @ApiResource(
 * routePrefix="/admin",
 * 
 *              normalizationContext={"groups"={"profil:read"}},
 *              denormalizationContext={"groups"={"profil:write"}},
 * collectionOperations={
 *         "get"={
 *         "security"="is_granted('ROLE_ADMIN')", 
 *         "security_message"="seul les admins peuvent ajouter un profil."
 * },
 *         "post"={
 *         "security"="is_granted('ROLE_ADMIN')", 
 *         "security_message"="seul les admins peuvent ajouter un profil."
 * }
 *     },
 * itemOperations={
 *         "get"={
 *         "security"="is_granted('ROLE_ADMIN')", 
 *         "security_message"="seul les admins peuvent ajouter un profil."
 * },
 *         "put"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="seul les admins peuvent modifier un profil."
 * },
 *         "delete"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="seul les admins peuvent supprimer un profil."
 * }
 *     },
 *      
 * )
 */

class Profil
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("profil:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profil:read", "profil:write", "user:read"})
     * @Assert\NotBlank(message = "Le libelle ne peut pas etre vide")
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     * @ApiSubresource
     * @Groups("profil:read")
     */
    private $users;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut=false ;


    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setProfil($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getProfil() === $this) {
                $user->setProfil(null);
            }
        }

        return $this;
    }
    public function __toString() {
        return $this->libelle;
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

}
