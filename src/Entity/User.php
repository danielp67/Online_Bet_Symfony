<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateInterval;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    private const PATTERN_NAME = "/^[a-zA-ZÀ-ÿ '-]{1,30}$/";
    private const PATTERN_EMAIL = "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/";
    private const PATTERN_PASS = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,16}$/";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer",
     * message="Id incorrect",
     * groups={"login"}
     * )
     * @Assert\GreaterThan(
     * value = 0,
     * message="Id incorrect",
     * groups={"login"}
     * )
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="{{ label }} vide",
     * groups={"username"}
     * )
     * @Assert\Regex(
     * pattern =  "/^[a-zA-ZÀ-ÿ '-]{1,30}$/",
     * message="{{ label }} : {{ value }} incorrect",
     * groups={"username"}
     * )
     */
    private string $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="{{ label }} vide",
     * groups={"username"}
     * )
     * @Assert\Regex(
     * pattern =  "/^[a-zA-ZÀ-ÿ '-]{1,30}$/",
     * message="{{ label }} : {{ value }} incorrect",
     * groups={"username"}
     * )
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="{{ label }} vide",
     * groups={"email"}
     * )
     * @Assert\Email(message="Format email incorrect",
     * groups={"email"}
     * )
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="{{ label }} vide",
     * groups={"password"}
     * )
     * @Assert\Regex(
     * pattern =  "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,16}$/",
     * message="Format password incorrect, 1 Majuscule, 1 Chiffre, 8 caractères minimum",
     * groups={"password"}
     * )
     */
    private string $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $birthDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $createDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $userValidation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $userValidationDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $userSuspended;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $userSuspendedDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $userDeleted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $userDeletedDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function getUserValidation(): ?bool
    {
        return $this->userValidation;
    }

    public function getUserValidationDate(): ?\DateTimeInterface
    {
        return $this->userValidationDate;
    }

    public function getUserSuspended(): ?bool
    {
        return $this->userSuspended;
    }

    public function getUserSuspendedDate(): ?\DateTimeInterface
    {
        return $this->userSuspendedDate;
    }

    public function getUserDeleted(): ?bool
    {
        return $this->userDeleted;
    }

    public function getUserDeletedDate(): ?\DateTimeInterface
    {
        return $this->userDeletedDate;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of lastName
     *
     * @return  self
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Set the value of firstName
     *
     * @return  self
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set the value of birthDate
     *
     * @return  self
     */
    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $minAge18 = (new DateTime())->sub(new DateInterval('P18Y'));

        if ($birthDate > $minAge18) {
            throw new InvalidArgumentException('Utilisateur n\'a pas encore 18 ans');
        }
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Set the value of createDate
     *
     * @return  self
     */
    public function setCreateDate(\DateTimeInterface $createDate): self
    {
        $minCreationDate = new DateTime();

        if ($createDate > $minCreationDate) {
            throw new InvalidArgumentException('Date de création invalide');
        }
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Set the value of userValidation
     *
     * @return  self
     */
    public function setUserValidation(bool $userValidation): self
    {
        $this->userValidation = $userValidation;

        return $this;
    }

    /**
     * Set the value of userValidationDate
     *
     * @return  self
     */
    public function setUserValidationDate(?\DateTimeInterface $userValidationDate): self
    {
        $minValidationDate = new DateTime();

        if ($userValidationDate > $minValidationDate && isset($userValidationDate)) {
            throw new InvalidArgumentException('Date de validation invalide');
        }
        $this->userValidationDate = $userValidationDate;

        return $this;
    }

    /**
     * Set the value of userSuspended
     *
     * @return  self
     */
    public function setUserSuspended(bool $userSuspended)
    {
        $this->userSuspended = $userSuspended;

        return $this;
    }

    /**
     * Set the value of userSuspendedDate
     *
     * @return  self
     */
    public function setUserSuspendedDate(?\DateTimeInterface $userSuspendedDate): self
    {
        $minSuspendedDate = new DateTime();

        if ($userSuspendedDate > $minSuspendedDate && isset($userSuspendedDate)) {
            throw new InvalidArgumentException('Date de suspension invalide');
        }
        $this->userSuspendedDate = $userSuspendedDate;

        return $this;
    }

    /**
     * Set the value of userDeleted
     *
     * @return  self
     */
    public function setUserDeleted(bool $userDeleted)
    {
        $this->userDeleted = $userDeleted;

        return $this;
    }

    /**
     * Set the value of userDeletedDate
     *
     * @return  self
     */
    public function setUserDeletedDate(?\DateTimeInterface $userDeletedDate): self
    {
        $minDeletedDate = new DateTime();

        if ($userDeletedDate > $minDeletedDate && isset($userDeletedDate)) {
            throw new InvalidArgumentException('Date de suppression invalide');
        }
        $this->userDeletedDate = $userDeletedDate;

        return $this;
    }

    /**
     * Entity builder with requested parameters
     *
     * @return  self
     */
    public static function build(
        ?string $firstName,
        ?string $lastName,
        ?string $email,
        ?string $password,
        ?string $birthDate
    ): User {
        $user = new User();
        $firstName ? $user->setFirstName($firstName) : null ;
        $lastName ? $user->setLastName($lastName) : null ;
        $email ? $user->setEmail($email) : null ;
        $password ? $user->setPassword($password) : null ;
        $birthDate ? $user->setBirthDate(DateTime::createFromFormat('Y-m-d', $birthDate)) : null ;

        $user->setCreateDate(new DateTime())
            ->setUserValidation(false)
            ->setUserSuspended(true)
            ->setUserDeleted(false);

        return $user;
    }
}
