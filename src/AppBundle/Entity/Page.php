<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Page
 *
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PageRepository")
 * @UniqueEntity("shortName")
 *
 */
class Page
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
     * @Assert\NotBlank()
     * @ORM\Column(name="shortName", type="string", length=255, unique=true)
     */
    private $shortName;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="body", type="text")
     */
    private $body;
    
    /**
     * @ORM\ManyToMany(targetEntity="Puzzle", mappedBy="pages")
     */
    private $puzzles;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="pages")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Picture")
     * @ORM\JoinColumn(name="picture_id", referencedColumnName="id")
     *
     */
    private $picture;

    /**
     * Page constructor.
     */
    public function __construct() {
        $this->puzzles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set shortName
     *
     * @param string $shortName
     *
     * @return Page
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * Get shortName
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Page
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
    
    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Page
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add puzzle
     *
     * @param \AppBundle\Entity\Puzzle $puzzle
     *
     * @return Page
     */
    public function addPuzzle(\AppBundle\Entity\Puzzle $puzzle)
    {
        $this->puzzles[] = $puzzle;

        return $this;
    }

    /**
     * Remove puzzle
     *
     * @param \AppBundle\Entity\Puzzle $puzzle
     */
    public function removePuzzle(\AppBundle\Entity\Puzzle $puzzle)
    {
        $this->puzzles->removeElement($puzzle);
    }

    /**
     * Get puzzles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPuzzles()
    {
        return $this->puzzles;
    }

    /**
     * Set picture
     *
     * @param \AppBundle\Entity\Picture $picture
     *
     * @return Page
     */
    public function setPicture(\AppBundle\Entity\Picture $picture = null)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return \AppBundle\Entity\Picture
     */
    public function getPicture()
    {
        return $this->picture;
    }
}
