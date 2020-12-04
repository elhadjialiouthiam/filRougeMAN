<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeTagRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ApiFilter(
 * BooleanFilter::class, properties={"statut"}
 * )
 * @ApiResource(
 *       collectionOperations={
 *          "showGroupeTag"={
 *              "method"="GET",
 *              "path"="/admin/groupeTags",
 *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "normalization_context"={"groups"={"Groupe_tag:read"}}
 *          },
 *          "addGroupeTag"={
 *              "method"="POST",
 *              "path"="/admin/groupeTags",
 *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *          }
 *     },
 *      itemOperations={
 *          "showGroupeTagById"={
 *              "method"="GET",
 *              "path"="admin/groupeTags/{id}",
 *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "normalization_context"={"groups"={"Groupe_tag:read"}}
 *          },
 *          "editTag"={
 *              "method"="PUT",
 *              "path"="/admin/groupeTags/{id}",
 *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          "showTagsIdGroupeTag"={
 *              "method"="GET",
 *              "path"="/admin/groupeTags/{id}/tags",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *          },
 *          "addTagInGroupetag"={
 *              "method"="PUT",
 *              "path"="/admin/groupeTag/{id}",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *          },
 *          "deleteTagInGroupetag"={
 *              "method"="DELETE",
 *              "path"="/admin/groupeTags/{id}/tags/{id_tag}",
 *              "access_control"="(is_granted('ROLE_ADMIN'))",
 *          },
 *          "delete"={
 *              "method"="DELETE",
 *              "path"="admin/groupeTags/{id}",
 *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *      }
 * )
 * @ORM\Entity(repositoryClass=GroupeTagRepository::class)
 */
class GroupeTag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="groupeTags")
     */
    private $tag;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut=false;

    public function __construct()
    {
        $this->tag = new ArrayCollection();
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
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tag->contains($tag)) {
            $this->tag->removeElement($tag);
        }

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
}
