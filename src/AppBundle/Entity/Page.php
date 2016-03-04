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
     * @var string
     *
     * @ORM\Column(name="unlockQuestion", type="text", nullable=true)
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
     * @ORM\Column(name="unlockAnswer", type="string", length=255, nullable=true)
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
     *  @Assert\Expression(
     *     "not (this.getquestionPicture() != null and this.getUnlockQuestion() == null)",
     *     message="You have to add question"
     * )
     */
    private $questionPicture;


    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="unlockedPages")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="pages")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;


    /**
     * Page constructor.
     */
    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set unlockQuestion
     *
     * @param string $unlockQuestion
     *
     * @return Page
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
     * @return Page
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
     * Add user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Page
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
     * Set questionPicture
     *
     * @param \AppBundle\Entity\Picture $questionPicture
     *
     * @return Page
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
}
