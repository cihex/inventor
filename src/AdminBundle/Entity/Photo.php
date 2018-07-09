<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Photo
 *
 * @ORM\Table(name="photo")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\PhotoRepository")
 */
class Photo
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
     * @var Exhibit|null
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\Exhibit", inversedBy="photos")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="exhibit_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     * })
     * @Assert\NotBlank()
     */
    private $exhibit;

    /**
     * @var bool
     * @ORM\Column(name="is_active", type="boolean", options={"default":1})
     */
    private $active = true;

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
     * @return Photo
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
     * @return Exhibit|null
     */
    public function getExhibit()
    {
        return $this->exhibit;
    }

    /**
     * @param  mixed $exhibit
     * @return $this
     */
    public function setExhibit($exhibit)
    {
        $this->exhibit = $exhibit;
        return $this;
    }

    /**
     * @return  bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param  bool $active
     * @return Photo
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    public function getPath()
    {

    }
}
