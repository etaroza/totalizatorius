<?php

namespace Toto\TotalizerBundle\Service;

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
}
