<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public const ROLE_PATIENT = 'ROLE_PATIENT';

    public const ROLE_DOCTOR = 'ROLE_DOCTOR';

    public const IMAGE_FOLDER = '/uploads/avatars/';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotNull(
        message: 'Будь ласка, заповніть це поле',
    )]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\Length([
        'min' => 8,
        'minMessage' => 'Ваш пароль повинен містити щонайменше {{ limit }} символів',
    ])]
    #[Assert\Regex(
        pattern: '/[!@#$%^&*()\-_=+{};:,<.>]/',
        message: 'Ваш пароль повинен містити принаймні один спеціальний символ',
        match: true,
    )]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(
        message: 'Будь ласка, заповніть це поле',
    )]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(
        message: 'Будь ласка, заповніть це поле',
    )]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatarPath = null;

    /**
     * @var Collection<int, Patient>
     */
    #[ORM\OneToMany(targetEntity: Patient::class, mappedBy: 'patient', orphanRemoval: true)]
    private Collection $patients;

    /**
     * @var Collection<int, Patient>
     */
    #[ORM\OneToMany(targetEntity: Patient::class, mappedBy: 'doctor', orphanRemoval: true)]
    private Collection $doctors;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    public function __construct()
    {
        $this->patients = new ArrayCollection();
        $this->doctors = new ArrayCollection();
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAvatarPath(): ?string
    {
        return $this->avatarPath;
    }

    public function setAvatarPath(?string $avatarPath): static
    {
        $this->avatarPath = $avatarPath;

        return $this;
    }

    public function getFullAvatarPath(): ?string
    {
        return $this->avatarPath ? self::IMAGE_FOLDER . $this->avatarPath : null;
    }

    /**
     * @return Collection<int, Patient>
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function addPatient(Patient $patient): static
    {
        if (!$this->patients->contains($patient)) {
            $this->patients->add($patient);
            $patient->setPatient($this);
        }

        return $this;
    }

    public function removePatient(Patient $patient): static
    {
        if ($this->patients->removeElement($patient)) {
            // set the owning side to null (unless already changed)
            if ($patient->getPatient() === $this) {
                $patient->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Patient>
     */
    public function getDoctors(): Collection
    {
        return $this->doctors;
    }

    public function addDoctor(Patient $patient): static
    {
        if (!$this->patients->contains($patient)) {
            $this->patients->add($patient);
            $patient->setDoctor($this);
        }

        return $this;
    }

    public function removeDoctor(Patient $patient): static
    {
        if ($this->patients->removeElement($patient)) {
            // set the owning side to null (unless already changed)
            if ($patient->getDoctor() === $this) {
                $patient->setDoctor(null);
            }
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }
}
