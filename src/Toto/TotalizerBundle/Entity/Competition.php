<?php

namespace Toto\TotalizerBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    Symfony\Component\Validator\Constraints as Assert,
    Toto\UserBundle\Entity\User;

/**
 * Competition
 *
 * @ORM\Table(name="competition")
 * @ORM\Entity
 */
class Competition
{
    /**
     * @var integer
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
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Toto\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="admin_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $admin;

    /**
     * @ORM\ManyToMany(targetEntity="Toto\UserBundle\Entity\User")
     * @ORM\JoinTable(name="competition_users",
     *      joinColumns={@ORM\JoinColumn(name="competition_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     */
    private $users;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Tournament")
     * @ORM\JoinColumn(name="tournament_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $tournament;

    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Competition
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
     * Gets the value of admin
     *
     * @return User
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Sets the value of admin
     *
     * @param User $admin 
     *
     * @return Competition
     */
    public function setAdmin(User $admin)
    {
        $this->admin = $admin;
        return $this;
    }

    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Gets the value of Tournament
     *
     * @return Tournament
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     * Sets the value of Tournament
     *
     * @param Tournament $Tournament description
     *
     * @return Competition
     */
    public function setTournament(Tournament $tournament)
    {
        $this->tournament = $tournament;
        return $this;
    }

    /**
     * __toString 
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
