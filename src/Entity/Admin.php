<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdminRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 */
class Admin extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity=Promos::class, mappedBy="admin")
     */
    private $promos;

    public function __construct()
    {
        $this->promos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $promo->setAdmin($this);
        }

        return $this;
    }

    public function removePromo(Promos $promo): self
    {
        if ($this->promos->contains($promo)) {
            $this->promos->removeElement($promo);
            // set the owning side to null (unless already changed)
            if ($promo->getAdmin() === $this) {
                $promo->setAdmin(null);
            }
        }

        return $this;
    }
}
