<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="LicencePlate", inversedBy="userIds")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length = 255)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */

    private string $emailAddress;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string Link to picture
     * @ORM\Column(type="string" )
     */
    private $profilePicture = "basic-img.png";

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $rating = 0;

    /**
     * @var int
     * @ORM\Column (type="integer")
     */
    private $numberOfRatings = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getEmailAddress():string
    {
        return $this->emailAddress;
    }

    /**
     * @param mixed $emailAddress
     */
    public function setEmailAddress($emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword():null|string
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword(mixed $password)
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
        return $this->emailAddress;
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->id;
    }

    /**
     * @return string
     */
    public function getProfilePicture(): string
    {
        return $this->profilePicture;
    }

    /**
     * @return float
     */
    public function getRating(): float|int
    {
        return $this->rating;
    }

    /**
     * @param float $rating
     * @return User
     */
    public function setRating(float|int $rating): User
    {
        $this->rating = $rating;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumberOfRatings(): int
    {
        return $this->numberOfRatings;
    }

    /**
     * @param int $numberOfRatings
     * @return User
     */
    public function setNumberOfRatings(int $numberOfRatings): User
    {
        $this->numberOfRatings = $numberOfRatings;
        return $this;
    }

    /**
     * @param string $profilePicture
     * @return User
     */
    public function setProfilePicture(string $profilePicture): User
    {
        $this->profilePicture = $profilePicture;
        return $this;
    }






}
