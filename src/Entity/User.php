<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
* @UniqueEntity(fields={"email"},message="Il existe déjà un compte avec cette adresse mail")

 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("formation:users")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups("formation:users")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups("formation:users")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("formation:users")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("formation:users")
     */
    private $prenom;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("formation:users")
     */
    private $date_creation_password;

    /**
     * @ORM\Column(type="array")
     */
    private $liste_pwd;

    /**
     * @ORM\Column(type="integer")
     * @Groups("formation:users")
     */
    private $etat_compte;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Formation", inversedBy="users")
     */
    private $formation;

    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $apiToken;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Candidature", mappedBy="user")
      * @ORM\OrderBy({ "DateEnvoieCandidature" = "DESC"})
     */
    private $candidatures;

    public function __construct()
    {
        $this->formation = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
    }

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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

   

   
    
    /**
     * @return Collection|formation[]
     */
    public function getFormation(): Collection
    {
        return $this->formation;
    }

    public function addFormation(formation $formation): self
    {
        if (!$this->formation->contains($formation)) {
            $this->formation[] = $formation;
        }

        return $this;
    }

    public function removeFormation(formation $formation): self
    {
        if ($this->formation->contains($formation)) {
            $this->formation->removeElement($formation);
        }

        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    /**
     * @return Collection|Candidature[]
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): self
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures[] = $candidature;
            $candidature->setUser($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->contains($candidature)) {
            $this->candidatures->removeElement($candidature);
            // set the owning side to null (unless already changed)
            if ($candidature->getUser() === $this) {
                $candidature->setUser(null);
            }
        }

        return $this;
    }

    public function getDateCreationPassword(): ?\DateTimeInterface
    {
        return $this->date_creation_password;
    }

    public function setDateCreationPassword(\DateTimeInterface $date_creation_password): self
    {
        $this->date_creation_password = $date_creation_password;

        return $this;
    }

    public function getListePwd(): ?array
    {
        return $this->liste_pwd;
    }

    public function setListePwd(array $liste_pwd): self
    {
        $this->liste_pwd = $liste_pwd;

        return $this;
    }

    public function getEtatCompte(): ?int
    {
        return $this->etat_compte;
    }

    public function setEtatCompte(int $etat_compte): self
    {
        $this->etat_compte = $etat_compte;

        return $this;
    }
}
