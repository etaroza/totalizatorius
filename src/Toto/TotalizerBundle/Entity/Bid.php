<?php

namespace Toto\TotalizerBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    Symfony\Component\Validator\Constraints as Assert;

/**
 * Bid
 *
 * @ORM\Table(name="bid")
 * @ORM\Entity
 */
class Bid
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
     * @ORM\ManyToOne(targetEntity="Toto\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Competition")
     * @ORM\JoinColumn(name="competition_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $competition;

    /**
     * @ORM\ManyToOne(targetEntity="Game")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $game;

    /**
     * @var integer
     *
     * @ORM\Column(name="score_home", type="integer")
     */
    private $scoreHome;

    /**
     * @var integer
     *
     * @ORM\Column(name="score_away", type="integer")
     */
    private $scoreAway;

    /**
     * @var integer
     *
     * @ORM\Column(name="points", type="integer", nullable=true)
     */
    private $points;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime;
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
     * Set user
     *
     * @param string $user
     * @return Bid
     */
    public function setUser($user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set competition
     *
     * @param string $competition
     * @return Bid
     */
    public function setCompetition($competition)
    {
        $this->competition = $competition;
    
        return $this;
    }

    /**
     * Get competition
     *
     * @return string 
     */
    public function getCompetition()
    {
        return $this->competition;
    }

    /**
     * Set game
     *
     * @param string $game
     * @return Bid
     */
    public function setGame($game)
    {
        $this->game = $game;
    
        return $this;
    }

    /**
     * Get game
     *
     * @return string 
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set scoreHome
     *
     * @param string $scoreHome
     * @return Bid
     */
    public function setScoreHome($scoreHome)
    {
        $this->scoreHome = $scoreHome;
    
        return $this;
    }

    /**
     * Get scoreHome
     *
     * @return string 
     */
    public function getScoreHome()
    {
        return $this->scoreHome;
    }

    /**
     * Set scoreAway
     *
     * @param integer $scoreAway
     * @return Bid
     */
    public function setScoreAway($scoreAway)
    {
        $this->scoreAway = $scoreAway;
    
        return $this;
    }

    /**
     * Get scoreAway
     *
     * @return integer 
     */
    public function getScoreAway()
    {
        return $this->scoreAway;
    }

    /**
     * Set points
     *
     * @param integer $points
     * @return Bid
     */
    public function setPoints($points)
    {
        $this->points = $points;
    
        return $this;
    }

    /**
     * Get points
     *
     * @return integer 
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Bid
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
