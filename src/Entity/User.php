<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;






/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * * @ORM\InheritanceType("JOINED")
* @ORM\DiscriminatorColumn(name="type", type="string")
* @ORM\DiscriminatorMap({"user" = "User", "administrateur" = "SuperAdmin"})
 * @UniqueEntity(
     *fields = {"username", "email", "telephone"},
     *message = "l'email, le username ou le numéro de téléphone est est déjà utilisé, veuillez choisir un autre"
 * )
 * @ApiResource(
 *     normalizationContext = {"groups" = {"admin:read"}},
 *     denormalizationContext = {"groups" = {"admin:write"}},
 *     attributes = {
 *          "security" = "is_granted('ROLE_Super Admin')",
 *          "security_message" = "vous n'avez pas accés à cette ressource",
 *          "pagination_enabled" = true,
 *          "pagination_items_per_page" = 3
 * },
 *     routePrefix = "/nopale",
 *     collectionOperations = {"get",
 *      "add_user" = {
 *           "method" = "POST",
 *           "route_name" = "add_user"}
 * },
 *     itemOperations = {"get",
 *      "edit_user" = {
 *           "deserialize" = false,
 *           "method" = "PUT",
 *           "route_name" = "edit_user"},
 *      "delete"}
 * )
 * @ApiFilter(SearchFilter::class, properties={"profil" : "exact"})
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"admin:read"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Le nom d'utilisateur ne peut pas etre null")
     * @Groups({"admin:read"})
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin:read"})
     *  @Assert\NotBlank(message="Le prénom ne peut pas etre null")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin:read"})
     *  @Assert\NotBlank(message="Le nom ne peut pas etre null")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin:read"})
     *  @Assert\NotBlank(message="Le numéro de téléphone ne peut pas etre null")
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin:read"})
     *  @Assert\NotBlank(message="L'adresse email ne peut pas etre null")
     */
    private $email;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"admin:read"})
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin:read"})
     *  @Assert\NotBlank(message="L'adresse ne peut pas etre null")
     */
    private $adresse;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @Groups({"admin:read"})
     *  @Assert\NotBlank(message="Le profil ne peut pas etre null")
     */
    private $profil;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBlocked;


    public function __construct()
    {
        $this->isBlocked = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this -> profil -> getLiBelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
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

    public function getPhoto()
    {
        $avatar = $this->photo;
        if (is_resource($avatar)) {
            return base64_encode(stream_get_contents($avatar));
        }
        return $avatar;
    }

    public function setPhoto($photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

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

    public function getIsBlocked(): ?bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(bool $isBlocked): self
    {
        $this->isBlocked = $isBlocked;

        return $this;
    }
}
