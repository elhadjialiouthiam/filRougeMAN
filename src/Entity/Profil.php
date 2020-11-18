<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 * @ApiResource(
 * routePrefix="/admin",
 * collectionOperations={
 *         "get",
 *         "post"={"security"="is_granted('ROLE_ADMIN')", "security_message"="seul les admins peuvent ajouter un profil."}
 *     },
 * itemOperations={
 *         "get",
 *         "put"={"security"="is_granted('ROLE_ADMIN')", "security_message"="seul les admins peuvent modifier un profil."},
 *         "delete"={"security"="is_granted('ROLE_ADMIN')", "security_message"="seul les admins peuvent supprimer un profil."}
 *     },
 *      
 *              normalizationContext={"groups"={"profil:read"}},
 *              denormalizationContext={"groups"={"profil:write"}}
 * )
 */
class Profil
{
    const ALLOWED_PROFILS = ["admin", "formateur", "CM","apprenant","vigil"];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("profil:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profil:read", "profil:write"})
     * @Assert\NotBlank(message = "Le libelle ne peut pas etre vide")
     * @Assert\Choice(choices=Profil::ALLOWED_PROFILS, message="Donner un profil valide")
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     */
    private $users;


    private $statut;

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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
}
