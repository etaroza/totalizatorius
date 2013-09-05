<?php

namespace Toto\TotalizerBundle\Service;

use Toto\TotalizerBundle\Entity;

/**
 * Service for game operations
 */
class Game
{
    /**
     * Entity manager
     */
    protected $em;

    /**
     * Repository
     */
    protected $repo;

    /**
     * Constructor.
     *
     * @param mixed $doctrine Doctrine object
     *
     * @return \Toto\TotalizerBundle\Service\Game
     *
     */
    public function __construct($doctrine)
    {
        $this->em = $doctrine->getManager();
        $this->repo = $this->em->getRepository('TotoTotalizerBundle:Game');
    }

    /**
     * getGameById 
     *
     * @param int $id game id
     *
     * @return Game
     */
    public function getGameById($id)
    {
        return $this->repo->find($id);
    }

    public function createNew($time, $teams)
    {
        $game = $this->repo->findOneBy(array(
            'teamHome' => $teams['home'],
            'teamAway' => $teams['away'],
            'time' => $time,
        ));

        if ($game) {
            return false;
        }

        $game = new Entity\Game();
        $game->setTeamHome($teams['home']);
        $game->setTeamAway($teams['away']);
        $game->setTime($time);

        $this->em->persist($game);
    }

    public function updateScore($time, $teams, $scores)
    {
        $game = $this->repo->findOneBy(array(
            'teamHome' => $teams['home'],
            'teamAway' => $teams['away'],
            'time' => $time,
        ));

        if (!$game) {
            return false;
        }

        if ($game->getScoreHome() || $game->getScoreAway()) {
            return false;
        }

        $game->setScoreHome($scores['home']);
        $game->setScoreAway($scores['away']);

        $this->em->persist($game);
    }

    public function save()
    {
        $this->em->flush();
    }
}
