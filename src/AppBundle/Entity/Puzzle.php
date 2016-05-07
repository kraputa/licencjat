<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Page
 *
 * @ORM\Table(name="puzzles")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PuzzleRepository")
 *
 */
class Puzzle
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
     * @var string
     *
     * @ORM\Column(name="unlockQuestion", type="text",)
     * @Assert\Expression(
     *     "not (this.getUnlockQuestion() != null and this.getUnlockAnswer() == null)",
     *     message="You have to add answer to question"
     * )
     *
     * @Assert\Expression(
     *     "not (this.getUnlockAnswer() != null and this.getUnlockQuestion() == null)",
     *     message="You have to add question"
     * )
     *
     */
    private $unlockQuestion;

    /**
     * @var string
     *
     * @ORM\Column(name="unlockAnswer", type="string", length=255)
     * @Assert\Expression(
     *     "not (this.getUnlockQuestion() != null and this.getUnlockAnswer() == null)",
     *     message="You have to add answer to question"
     * )
     *
     * @Assert\Expression(
     *     "not (this.getUnlockAnswer() != null and this.getUnlockQuestion() == null)",
     *     message="You have to add question"
     * )
     *
     */
    private $unlockAnswer;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Picture")
     * @ORM\JoinColumn(name="questionPicture_id", referencedColumnName="id")
     *
     */
    private $questionPicture;


    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="unlockedPuzzles")
     */
    private $users;


    /**
     * @ORM\ManyToMany(targetEntity="Page", inversedBy="puzzles")
     * @ORM\JoinTable(name="pages_puzzles")
     */
    private $pages;


    /**
     * Page constructor.
     */
    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Puzzle
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
     * @return Puzzle
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
     * Set unlockQuestion
     *
     * @param string $unlockQuestion
     *
     * @return Puzzle
     */
    public function setUnlockQuestion($unlockQuestion)
    {
        $this->unlockQuestion = $unlockQuestion;

        return $this;
    }

    /**
     * Get unlockQuestion
     *
     * @return string
     */
    public function getUnlockQuestion()
    {
        return $this->unlockQuestion;
    }

    /**
     * Set unlockAnswer
     *
     * @param string $unlockAnswer
     *
     * @return Puzzle
     */
    public function setUnlockAnswer($unlockAnswer)
    {
        $this->unlockAnswer = $unlockAnswer;

        return $this;
    }

    /**
     * Get unlockAnswer
     *
     * @return string
     */
    public function getUnlockAnswer()
    {
        return $this->unlockAnswer;
    }

    /**
     * Set questionPicture
     *
     * @param \AppBundle\Entity\Picture $questionPicture
     *
     * @return Puzzle
     */
    public function setQuestionPicture(\AppBundle\Entity\Picture $questionPicture = null)
    {
        $this->questionPicture = $questionPicture;

        return $this;
    }

    /**
     * Get questionPicture
     *
     * @return \AppBundle\Entity\Picture
     */
    public function getQuestionPicture()
    {
        return $this->questionPicture;
    }

    /**
     * Add user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Puzzle
     */
    public function addUser(\AppBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \AppBundle\Entity\User $user
     */
    public function removeUser(\AppBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add page
     *
     * @param \AppBundle\Entity\Page $page
     *
     * @return Puzzle
     */
    public function addPage(\AppBundle\Entity\Page $page)
    {
        $this->pages[] = $page;

        return $this;
    }

    /**
     * Remove page
     *
     * @param \AppBundle\Entity\Page $page
     */
    public function removePage(\AppBundle\Entity\Page $page)
    {
        $this->pages->removeElement($page);
    }

    /**
     * Get pages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPages()
    {
        return $this->pages;
    }
}
