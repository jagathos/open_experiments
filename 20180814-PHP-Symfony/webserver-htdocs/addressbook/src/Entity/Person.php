<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Assert\Type(
     *    type = "integer",
     *    message = "L'identifiant doit être de type entier"
     * )
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(
     *    message = "Le nom ne peut être vide"
     * )
     * @Assert\Length(
     *    min = 1,
     *    max = 64,
     *    minMessage = "Le nom doit comporter au moins 1 caractère",
     *    maxMessage = "Le nom doit comporter au maximum 64 caractères"
     * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(
     *    message = "Le prénom ne peut être vide"
     * )
     * @Assert\Length(
     *    min = 1,
     *    max = 64,
     *    minMessage = "Le prénom doit comporter au moins 1 caractère",
     *    maxMessage = "Le prénom doit comporter au maximum 64 caractères"
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Assert\Regex(
     *    pattern="/^[0-9\-\(\)\/\+\s]*$/",
     *    message="Le numéro de téléphone n'est pas valide"
     * )
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @Assert\Email(
     *    message = "L'email n'est pas valide"
     * )
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Occupation")
     */
    private $occupation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $retired;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime(
     *    message = "Le champ date de création ne peut pas être décodé"
     * )
     */
    private $createtimestamp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getOccupation(): ?Occupation
    {
        return $this->occupation;
    }

    public function setOccupation(?Occupation $occupation): self
    {
        $this->occupation = $occupation;

        return $this;
    }

    public function getRetired(): ?bool
    {
        return $this->retired;
    }

    public function setRetired(bool $retired): self
    {
        $this->retired = $retired;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCreatetimestamp(): ?\DateTimeInterface
    {
        return $this->createtimestamp;
    }

    public function setCreatetimestamp(?\DateTimeInterface $createtimestamp): self
    {
        $this->createtimestamp = $createtimestamp;

        return $this;
    }

    /**
     * @Assert\IsTrue(
     *    message = "L'email ou le téléphone doit être renseigné"
     * )
     */
    public function hasPhoneOrEmail(): bool {
        return $this->email || $this->telephone;
    }
}
