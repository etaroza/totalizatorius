<?php

namespace Toto\TotalizerBundle\Service;

use Toto\UserBundle\Service\User;

/**
 * Service for competition operations
 */
class Competition
{
    /**
     * Entity manager
     */
    protected $em;

    /**
     * Repository
     */
    protected $repo;

    protected $userService;

    /**
     * Constructor.
     *
     * @param mixed $doctrine    Doctrine object
     * @param User  $userService User service
     *
     * @return \Toto\TotalizerBundle\Service\Competition
     *
     */
    public function __construct($doctrine, User $userService)
    {
        $this->userService = $userService;
        $this->em = $doctrine->getManager();
        $this->repo = $this->em->getRepository('TotoTotalizerBundle:Competition');
    }

    /**
     * getUserCompetitions 
     * 
     * @return array
     */
    public function getUserCompetitions()
    {
        $currentUser = $this->userService->getCurrentUser();

        $qb = $this->repo->createQueryBuilder('c');
        $qb->innerJoin('c.users', 'u')
            ->andWhere('u.id = :userId');
        $qb->setParameter('userId', $currentUser->getId());

        return $qb->getQuery()->getResult();
    }

    /**
     * getCompetitionById 
     *
     * @param int $id competition id
     *
     * @return Competition
     */
    public function getCompetitionById($id)
    {
        return $this->repo->find($id);
    }
}
