<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 * @UniqueEntity(fields="libelle",message="Le libelle des profils doit être unique")
 * @ApiResource(
 *      collectionOperations=
 *          {
 *                   "GET"={
 *                          "path"="/admin/profils"
 *                          },
 *                    "POST"={"path"="/admin/profils"},
 *          },
 *       itemOperations=
 *          {
 *                   "get_profil"=
 *                          {
 *                             "method"="GET",
 *                              "path"="/admin/profils/{id}"
 *                           },
 *                   "update_profile"=
 *                           {
 *                            "method"="put",
 *                            "path"="/admin/profils/{id}"
 *                           }
 *          }
 * )
 */
class Profil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"admin:read"})
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="le libellé du profil ne doit pas être null")
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     */
    private $users;

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
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getProfil() === $this) {
                $user->setProfil(null);
            }
        }

        return $this;
    }
}
