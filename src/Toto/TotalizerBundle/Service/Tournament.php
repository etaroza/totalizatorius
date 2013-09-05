<?php

namespace Toto\TotalizerBundle\Service;

use Doctrine\ORM\EntityManager;
use Toto\TotalizerBundle\Entity;

/**
 * Service for tournament operations
 */
class Tournament
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
        $this->repo = $this->em->getRepository('TotoTotalizerBundle:Tournament');
    }

    /**
     * @return Tournament
     */
    public function getById($id)
    {
        return $this->repo->find($id);
    }
}
