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
     * @ORM\ManyToMany(targetEntity="Page", inversedBy="users")
     * @ORM\JoinTable(name="users_unlockedpages")
     */
    protected $unlockedPages;

    public function __construct()
    {
        parent::__construct();
        $this->unlockedPages = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * Add unlockedPage
     *
     * @param \AppBundle\Entity\Page $unlockedPage
     *
     * @return User
     */
    public function addUnlockedPage(\AppBundle\Entity\Page $unlockedPage)
    {
        $this->unlockedPages[] = $unlockedPage;

        return $this;
    }

    /**
     * Remove unlockedPage
     *
     * @param \AppBundle\Entity\Page $unlockedPage
     */
    public function removeUnlockedPage(\AppBundle\Entity\Page $unlockedPage)
    {
        $this->unlockedPages->removeElement($unlockedPage);
    }

    /**
     * Get unlockedPages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUnlockedPages()
    {
        return $this->unlockedPages;
    }
}
