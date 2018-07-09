<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hire
 *
 * @ORM\Table(name="hire")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\HireRepository")
 */
class Hire
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255, nullable=true)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="postal_code", type="string", length=255, nullable=true)
     * @Assert\Regex("/^[0-9]{2}-[0-9]{3}$/")
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hireDate", type="date", nullable=true)
     */
    private $hireDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="plannedReturnDate", type="date", nullable=true, options={})
     */
    private $plannedReturnDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="returnDate", type="date", nullable=true, options={"default":NULL})
     */
    private $returnDate;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", length=255, nullable=true)
     */
    private $comment;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AdminBundle\Entity\Exhibit", mappedBy="hires")
     */
    private $exhibits;

    /**
     * Hire constructor.
     */
    public function __construct()
    {
        $this->exhibits = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Hire
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     *
     * @return Hire
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return Hire
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Hire
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $postalCode
     * @return $this
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Hire
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Hire
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set hireDate
     *
     * @param \DateTime $hireDate
     *
     * @return Hire
     */
    public function setHireDate($hireDate)
    {
        $this->hireDate = $hireDate;

        return $this;
    }

    /**
     * Get hireDate
     *
     * @return \DateTime
     */
    public function getHireDate()
    {
        return $this->hireDate;
    }

    /**
     * Set plannedReturnDate
     *
     * @param \DateTime $plannedReturnDate
     *
     * @return Hire
     */
    public function setPlannedReturnDate($plannedReturnDate)
    {
        $this->plannedReturnDate = $plannedReturnDate;

        return $this;
    }

    /**
     * Get plannedReturnDate
     *
     * @return \DateTime
     */
    public function getPlannedReturnDate()
    {
        return $this->plannedReturnDate;
    }

    /**
     * Set returnDate
     *
     * @param \DateTime $returnDate
     *
     * @return Hire
     */
    public function setReturnDate($returnDate)
    {
        $this->returnDate = $returnDate;

        return $this;
    }

    /**
     * Get returnDate
     *
     * @return \DateTime
     */
    public function getReturnDate()
    {
        return $this->returnDate;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Hire
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return  ArrayCollection
     */
    public function getExhibits()
    {
        return $this->exhibits;
    }

    /**
     * @param  ArrayCollection $exhibits
     * @return Hire
     */
    public function setExhibits($exhibits)
    {
        $this->exhibits = $exhibits;
        return $this;
    }

    /**
     * @return bool
     */
    public function isReturned()
    {
        return $this->returnDate != null;
    }

    /**
     * @return bool
     */
    public function isOutOfTime()
    {
        return $this->plannedReturnDate->setTime(0, 0, 0) < (new \DateTime())->setTime(0, 0, 0);
    }

    /**
     * @return bool
     */
    public function isPlanned()
    {
        return $this->hireDate->setTime(0, 0, 0) > (new \DateTime())->setTime(0, 0, 0);
    }
}
