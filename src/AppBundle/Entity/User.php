<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="app_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\ManyToMany(targetEntity="Puzzle", inversedBy="users")
     * @ORM\JoinTable(name="users_unlockedpuzzles")
     */
    protected $unlockedPuzzles;

    public function __construct()
    {
        parent::__construct();
        $this->unlockedPuzzles = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add unlockedPuzzle
     *
     * @param \AppBundle\Entity\Puzzle $unlockedPuzzle
     *
     * @return User
     */
    public function addUnlockedPuzzle(\AppBundle\Entity\Puzzle $unlockedPuzzle)
    {
        $this->unlockedPuzzles[] = $unlockedPuzzle;

        return $this;
    }

    /**
     * Remove unlockedPuzzle
     *
     * @param \AppBundle\Entity\Puzzle $unlockedPuzzle
     */
    public function removeUnlockedPuzzle(\AppBundle\Entity\Puzzle $unlockedPuzzle)
    {
        $this->unlockedPuzzles->removeElement($unlockedPuzzle);
    }

    /**
     * Get unlockedPuzzles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUnlockedPuzzles()
    {
        return $this->unlockedPuzzles;
    }
}
