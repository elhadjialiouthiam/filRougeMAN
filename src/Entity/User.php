<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\InheritanceType;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email")
 * @ApiFilter(
 * BooleanFilter::class, properties={"statut"}
 * )
 * @ApiResource(
 * routePrefix="/admin",
 * denormalizationContext={"groups":{"user:write"}},
 * normalizationContext={"groups":{"user:read"}},
 * collectionOperations={
 *         "show_users"={
 *                  "method"="GET",
 *                  "access_control"="(is_granted('ROLE_ADMIN'))"
 *          },
 *         "add_admin"={
 *                  "route_name"="add_admin",
 *                  "method"="POST",
 *                  "security" = "is_granted('ROLE_ADMIN')",
 *                  "security_message" = "Accès refusé"
 *          },
 *          "add_formateur"={
 *                  "route_name"="add_formateur",
 *                  "method"="POST",
 *                  "security" = "is_granted('ROLE_ADMIN')",
 *                  "security_message" = "Accès refusé"
 *          },
 *          "add_apprenant"={
 *                  "route_name"="add_apprenant",
 *                  "method"="POST",
 *                  "security" = "is_granted('ROLE_ADMIN')",
 *                  "security_message" = "Accès refusé"
 *          },
 *          "add_cm"={
 *                  "route_name"="add_cm",
 *                  "method"="POST",
 *                  "security" = "is_granted('ROLE_ADMIN')",
 *                  "security_message" = "Accès refusé"
 *          },
 *     },
 * itemOperations={
 *         "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="seul les admins peuvent modifier un profil."
 * },
 *         "edit_user"={
 *              "route_name"="edit_user",
 *              "method"="PUT",
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="seul les admins peuvent modifier un profil.",
 *              
 * },
 *         "delete"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="seul les admins peuvent supprimer un utilusateur."
 * },
 *     }
 * )
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"apprenant"="Apprenant", "formateur"="Formateur", "admin"="Admin", "cm"="CM", "user"="User"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     * message = "L'email ne peut pas etre vide"
     * )
     * @Assert\Regex(
     * pattern="/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/",
     * message="Email Invalide"
     * )
     * @Groups({"user:read","user:write"})
     */
    protected $email;
    
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Le prenom ne peut pas etre vide")
     * @Assert\Length(min = 3)
     * @Groups({"user:read","user:write"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Le nom ne peut pas etre vide")
     * @Assert\Length(min = 3)
     * @Groups({"user:read","user:write"})
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @Groups({"user:write", "user:read"})
     */
    private $profil;
    /** 
    *@ORM\Column(type="blob", nullable=true)
    *@Groups({"user:write" ,"user:read"})
    */
    
    private $avatar;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Statut=false ;

    private $type;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.strtoupper($this->profil->getLibelle());
        
        return array_unique($roles);
    }
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
    /*
    *@see UserInterface
    */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getAvatar()
    {
        if($this->avatar)
        {
            $data= \stream_get_contents($this->avatar);
            fclose($this->avatar);

            return base64_encode($data);
        }
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }
    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
        /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getStatut(): ?bool
    {
        return $this->Statut;
    }

    public function setStatut(bool $Statut): self
    {
        $this->Statut = $Statut;

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



}
