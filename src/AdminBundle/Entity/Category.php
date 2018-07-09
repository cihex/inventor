<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\CategoryRepository")
 */
class Category
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=20, unique=true)
     */
    private $alias;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime")
     */
    private $dateTime;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AdminBundle\Entity\Exhibit", mappedBy="category")
     */
    private $exhibits;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->exhibits = new ArrayCollection();
        $this->dateTime = new \DateTime();
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
     * @return Category
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
     * Set alias
     *
     * @param string $alias
     *
     * @return Category
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $dateTime
     *
     * @return Category
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param Exhibit $exhibit
     * @return $this
     */
    public function addExhibit(Exhibit $exhibit)
    {
        $this->exhibits->add($exhibit);
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getExhibits()
    {
        return $this->exhibits;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name . ' (' . $this->getAlias() . ')';
    }
}
