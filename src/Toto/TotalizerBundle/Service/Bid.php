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
    public function getUserBidsByCompetition($competitionId, \DateTime $since = null, \DateTime $until = null)
    {
        $currentUser = $this->userService->getCurrentUser();

        $where = [1];
        if ($since) {
            $where[] = sprintf("time >= '%s'", $since->format("Y-m-d H:i:s"));
        }
        if ($until) {
            $where[] = sprintf("time < '%s'", $until->format("Y-m-d H:i:s"));
        }

        $conn = $this->em->getConnection();
        $query = sprintf("SELECT g.*, b.id AS bid_id, b.score_home AS bid_score_home, b.score_away AS bid_score_away, b.points,
                th.name as team_h, ta.name as team_a
            FROM game g
            LEFT JOIN team th ON g.team_home = th.id
            LEFT JOIN team ta ON g.team_away = ta.id
            LEFT JOIN tournament t ON th.tournamend_id = t.id
            LEFT JOIN competition c ON c.tournament_id = t.id
            LEFT JOIN bid b ON b.competition_id = c.id
                AND b.game_id = g.id
                AND b.user_id = :userId 
            WHERE c.id = :competitionId AND %s
            ORDER BY g.time ASC", implode(' AND ', $where));

        $statement = $conn->prepare($query);
        $statement->bindValue(':competitionId', $competitionId, \PDO::PARAM_INT);
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

            //Update only not started games.
            if ($game->getTime() > new \DateTime) {
                $bid = $this->getBid($currentUser, $competition, $game);
                $bid->setScoreHome($teamScore['home']);
                $bid->setScoreAway($teamScore['away']);
                $this->em->persist($bid);
            }
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
    
    public function getEmptyBids()
    {
        $conn = $this->em->getConnection();
        $query = "SELECT b.id bid_id, g.score_home result_home, g.score_away result_away, b.score_home bid_home, b.score_away bid_away
            FROM  bid b
            LEFT JOIN game g
                ON b.game_id = g.id 
            WHERE b.points IS NULL
                AND g.score_home IS NOT NULL";

        $statement = $conn->prepare($query);
        $statement->execute();

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        
        return $result;
    }
    
    private function countPrecision($result1, $result2, $bid)
    {
        if(($result1 - $result2) < 11 && $bid == 1){
            return 3;
        }elseif(($result1 - $result2) < 21 && ($result1 - $result2) > 10 && $bid == 2){
            return 3;
        }elseif(($result1 - $result2) > 20 && $bid == 3){
            return 3;
        }else{
            return 1;
        }
    }
    
    public function countPoints($bid)
    {
        if($bid['result_home'] > $bid['result_away'] && $bid['bid_home'] > 0){
            return $this->countPrecision($bid['result_home'], $bid['result_away'], $bid['bid_home']);
        }elseif($bid['result_home'] < $bid['result_away'] && $bid['bid_away'] > 0){
            return $this->countPrecision($bid['result_away'], $bid['result_home'], $bid['bid_away']);
        }else{
            return 0;
        }
    }
    
    public function updatePoints($bidId, $points){
        $conn = $this->em->getConnection();
        $query = "UPDATE bid SET points = :points WHERE id = :bidId";

        $statement = $conn->prepare($query);
        $statement->bindValue(':points', $points, \PDO::PARAM_INT);
        $statement->bindValue(':bidId', $bidId, \PDO::PARAM_INT);
        return $statement->execute();
        
    }
}
