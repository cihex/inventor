<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Exhibit
 * @ORM\Table(name="exhibit")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\ExhibitRepository")
 */
class Exhibit
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
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="exhibits")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * })
     * @Assert\NotBlank()
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="adoption_date", type="date", nullable=true)
     * @Assert\Date()
     */
    private $adoptionDate;

    /**
     * @var Donor
     *
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\Donor", inversedBy="exhibits")
     * @ORM\JoinColumn(name="donor_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @Assert\NotBlank()
     */
    private $donor;


    /**
     * @var ExhibitState
     *
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\ExhibitState")
     * @ORM\JoinColumn(name="state_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @Assert\NotBlank()
     */
    private $state;

    /**
     * @var ExhibitOwner
     *
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\ExhibitOwner")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @Assert\NotBlank()
     */
    private $owner;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AdminBundle\Entity\Photo", mappedBy="exhibit")
     */
    private $photos;

    /**
     * @var string
     * @ORM\Column(name="producer", type="string", length=255, nullable=true)
     */
    private $producer;

    /**
     * @var int
     *
     * @ORM\Column(name="produce_date", type="integer", nullable=true, options={"default":"1990"})
     * @Assert\Range(
     *     min = 1800,
     *     max = 2017,
     *     minMessage = "Minimalna wartość to 1800.",
     *     maxMessage = "Data produkcji nie może być przyszłością."
     * )
     *
     */
    private $produceYear;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AdminBundle\Entity\Hire", inversedBy="exhibits")
     * @ORM\JoinTable(name="exhibits_hires")
     */
    private $hires;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_show_donor", type="boolean", nullable=false, options={"default":false})
     */
    private $showDonor;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_show_owner", type="boolean", nullable=false, options={"default":false})
     */
    private $showOwner;

    /**
     * Exhibit constructor.
     */
    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->hires = new ArrayCollection();
        $this->showDonor = true;
        $this->showOwner = true;
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
     * @return Exhibit
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
     * Set category
     *
     * @param Category $category
     *
     * @return Exhibit
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Exhibit
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set adoptionDate
     *
     * @param \DateTime $adoptionDate
     *
     * @return Exhibit
     */
    public function setAdoptionDate($adoptionDate)
    {
        $this->adoptionDate = $adoptionDate;

        return $this;
    }

    /**
     * Get adoptionDate
     *
     * @return \DateTime
     */
    public function getAdoptionDate()
    {
        return $this->adoptionDate;
    }

    /**
     * @return Donor
     */
    public function getDonor()
    {
        return $this->donor;
    }

    /**
     * @param Donor $donor
     * @return Exhibit
     */
    public function setDonor($donor)
    {
        $this->donor = $donor;
        return $this;
    }

    /**
     * @return ExhibitState
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param ExhibitState $state
     * @return Exhibit
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return ExhibitOwner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param  ExhibitOwner $owner
     * @return Exhibit
     */
    public function setOwner(ExhibitOwner $owner)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return ($this->getCategory() != null ? strtoupper($this->getCategory()->getAlias()) : 'B/K')
            . '/' . str_pad($this->getId(), 5, '0', STR_PAD_LEFT);
    }

    /**
     * @param Photo $photo
     * @return $this
     */
    public function addPhoto(Photo $photo)
    {
        $this->photos->add($photo);
        return $this;
    }

    /**
     * @param Photo $photo
     * @return $this
     *
     */
    public function removePhoto(Photo $photo)
    {
        $this->photos->removeElement($photo);
        $photo->setExhibit(null);
        return $this;
    }

    /**
     * @param ArrayCollection $photos
     * @return $this
     */
    public function setPhotos(ArrayCollection $photos)
    {
        $this->photos = $photos;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @return string
     */
    public function getProducer()
    {
        return (string)$this->producer;
    }

    /**
     * @param string $producer
     * @return Exhibit
     */
    public function setProducer(string $producer)
    {
        $this->producer = $producer;
        return $this;
    }

    /**
     * @return int
     */
    public function getProduceYear()
    {
        return $this->produceYear;
    }

    /**
     * @param int $produceYear
     * @return Exhibit
     */
    public function setProduceYear(int $produceYear)
    {
        $this->produceYear = $produceYear;
        return $this;
    }

    /**
     * @return Photo[]
     */
    public function getVisiblePhotos()
    {
        $visiblePhotos = [];
        foreach ($this->photos->getIterator() as $photo) {
            $visiblePhotos[] = $photo;
        }
        return $visiblePhotos;
    }

    /**
     * @return  ArrayCollection
     */
    public function getHires()
    {
        return $this->hires;
    }

    /**
     * @param  ArrayCollection $hires
     * @return Exhibit
     */
    public function setHires(ArrayCollection $hires)
    {
        $this->hires = $hires;
        return $this;
    }

    /**
     * @param \DateTime|null $date
     * @return boolean
     */
    public function isAvailable(\DateTime $date = null)
    {
        if ($date == null) {
            $date = new \DateTime();
        }
        /**
         * @var Hire $hire
         */
        foreach ($this->hires->getIterator() as $hire) {
            if (!$hire->isReturned() && $hire->getHireDate() !== null && $hire->getHireDate()->setTime(0, 0, 0) <= $date->setTime(0, 0, 0)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return boolean
     */
    public function isShowDonor()
    {
        return $this->showDonor;
    }

    /**
     * @param boolean $showDonor
     * @return $this
     */
    public function setShowDonor($showDonor)
    {
        $this->showDonor = $showDonor;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isShowOwner()
    {
        return $this->showOwner;
    }

    /**
     * @param boolean $showOwner
     * @return $this
     */
    public function setShowOwner($showOwner)
    {
        $this->showOwner = $showOwner;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '[' . $this->getNumber() . ']' . ' ' . $this->name;
    }
}
