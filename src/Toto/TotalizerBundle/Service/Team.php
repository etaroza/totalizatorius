<?php

namespace Toto\TotalizerBundle\Service;

use Doctrine\ORM\EntityManager;
use Toto\TotalizerBundle\Entity;

/**
 * Service for team operations
 */
class Team
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
     * @param EntityManager $doctrine Doctrine ORM entity manager
     *
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository('TotoTotalizerBundle:Team');
    }

    /**
     * @return Team
     */
    public function getByTournamentAndCode(Entity\Tournament $tournament, $code)
    {
        return $this->repo->findOneBy(array(
            'tournament' => $tournament,
            'code' => $code,
        ));
    }
}
