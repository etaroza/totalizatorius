<?php

namespace Toto\TotalizerBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Toto\UserBundle\Service\User;
use Toto\TotalizerBundle\Entity\Bid as BidEntity;

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
    protected $competitionService;
    protected $gameService;

    /**
     * Constructor.
     *
     * @param mixed $doctrine    Doctrine object
     * @param User  $userService User service
     *
     * @return \Toto\TotalizerBundle\Service\Bid
     *
     */
    public function __construct($doctrine, User $userService, $competitionService, $gameService)
    {
        $this->userService = $userService;
        $this->competitionService = $competitionService;
        $this->gameService = $gameService;
        $this->em = $doctrine->getManager();
        $this->repo = $this->em->getRepository('TotoTotalizerBundle:Bid');
    }

    /**
     * getUserBidsByCompetition 
     *
     * @param int $competitionId competition id
     *
     * @return array
     */
    public function getUserBidsByCompetition($competitionId)
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
        $statement->bindValue(':competitionId', $competitionId, \PDO::PARAM_INT);
        $statement->bindValue(':userId', $currentUser->getId(), \PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * updateBids 
     *
     * @param int     $competitionId competition id
     * @param Request $request       request
     *
     * @return void
     */
    public function updateBids($competitionId, Request $request)
    {
        $currentUser = $this->userService->getCurrentUser();
        $competition = $this->competitionService->getCompetitionById($competitionId);

        $games = $request->get('bids', array());
        foreach ($games as $gameId => $teamScore) {
            $game = $this->gameService->getGameById($gameId);

            $bid = $this->getBid($currentUser, $competition, $game);
            $bid->setScoreHome($teamScore['home']);
            $bid->setScoreAway($teamScore['away']);

            $this->em->persist($bid);
        }
        $this->em->flush();
    }

    /**
     * getBid or create new one
     * 
     * @param User        $user        user object
     * @param Competition $competition competition object
     * @param Game        $game        game object
     *
     * @return Bid
     */
    public function getBid($user, $competition, $game)
    {
        $bid = $this->repo->findOneBy(array(
            'user'        => $user,
            'competition' => $competition,
            'game'        => $game
        ));
        if (!$bid) {
            $bid = new BidEntity();
            $bid->setCompetition($competition);
            $bid->setUser($user);
            $bid->setGame($game);
        }

        return $bid;
    }
}
