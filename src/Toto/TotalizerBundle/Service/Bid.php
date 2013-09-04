<?php

namespace Toto\TotalizerBundle\Service;

use Toto\UserBundle\Service\User;

/**
 * Service for bid operations
 */
class Bid
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
     * @return \Toto\TotalizerBundle\Service\Bid
     *
     */
    public function __construct($doctrine, User $userService)
    {
        $this->userService = $userService;
        $this->em = $doctrine->getManager();
        $this->repo = $this->em->getRepository('TotoTotalizerBundle:Bid');
    }

    /**
     * getUserBidsByCompetition 
     *
     * @param int $id competition id
     *
     * @return array
     */
    public function getUserBidsByCompetition($id)
    {
        $currentUser = $this->userService->getCurrentUser();

        $conn = $this->em->getConnection();
        $query = "SELECT g.*, b.id AS bid_id, b.score_home AS bid_score_home, b.score_away AS bid_score_away, b.points,
                th.name as team_h, ta.name as team_a
            FROM game g
            LEFT JOIN team th ON g.team_home = th.id
            LEFT JOIN team ta ON g.team_away = ta.id
            LEFT JOIN tournament t ON th.tournamend_id = t.id
            LEFT JOIN competition c ON c.tournament_id = t.id
            LEFT JOIN bid b ON b.competition_id = c.id
                AND b.game_id = g.id
                AND b.user_id = :userId
            WHERE c.id = :competitionId
            ORDER BY g.time ASC ";

        $statement = $conn->prepare($query);
        $statement->bindValue(':competitionId', $id, \PDO::PARAM_INT);
        $statement->bindValue(':userId', $currentUser->getId(), \PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * Return grouped stats from bids table.
     * 
     * @param int $competitionId 
     * @return array
     */
    public function getStats($competitionId)
    {
        $dql = "SELECT b, SUM( b.points ) AS total
            FROM  Toto\\TotalizerBundle\\Entity\\Bid b
            WHERE b.competition = :competitionId
            GROUP BY b.user
            ORDER BY total DESC";

        $result = $this->em->createQuery($dql)
                      ->setParameter(':competitionId', $competitionId)
                      ->getResult();

        return $result;
    }
}
