<?php

namespace Toto\TotalizerBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    Symfony\Component\Validator\Constraints as Assert,
    Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Game
 *
 * @ORM\Table(name="game")
 * @ORM\Entity
 * @Assert\Callback(methods={"isTeamsValid"})
 */
class Game
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
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime")
     * @Assert\NotBlank()
     */
    private $time;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="team_home", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $teamHome;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="team_away", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $teamAway;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return Game
     */
    public function setTime($time)
    {
        $this->time = $time;
    
        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set teamHome
     *
     * @param integer $teamHome
     * @return Game
     */
    public function setTeamHome($teamHome)
    {
        $this->teamHome = $teamHome;
    
        return $this;
    }

    /**
     * Get teamHome
     *
     * @return integer 
     */
    public function getTeamHome()
    {
        return $this->teamHome;
    }

    /**
     * Set teamAway
     *
     * @param integer $teamAway
     * @return Game
     */
    public function setTeamAway($teamAway)
    {
        $this->teamAway = $teamAway;
    
        return $this;
    }

    /**
     * Get teamAway
     *
     * @return integer 
     */
    public function getTeamAway()
    {
        return $this->teamAway;
    }

    /**
     * Set scoreHome
     *
     * @param integer $scoreHome
     * @return Game
     */
    public function setScoreHome($scoreHome)
    {
        $this->scoreHome = $scoreHome;
    
        return $this;
    }

    /**
     * Get scoreHome
     *
     * @return integer 
     */
    public function getScoreHome()
    {
        return $this->scoreHome;
    }

    /**
     * Set scoreAway
     *
     * @param integer $scoreAway
     * @return Game
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
     * Check if both teams are not same.
     * 
     * @param ExecutionContextInterface $context 
     */
    public function isTeamsValid(ExecutionContextInterface $context)
    {
        if ($this->getTeamHome() == $this->getTeamAway()) {
            $context->addViolationAt('teamAway', 'Teams must be different!', array(), null);
        }
    }
}
