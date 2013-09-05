<?php
namespace Toto\ImportBundle\GameImporter;

use Doctrine\ORM\EntityManager;
use Toto\TotalizerBundle\Service\Game;
use Toto\TotalizerBundle\Service\Tournament;
use Toto\TotalizerBundle\Service\Team;

use Toto\ImportBundle\Importer\ImporterInterface;
use Toto\ImportBundle\Importer\AbstractImporter;


class Eurobasket2013Importer extends AbstractImporter implements ImporterInterface
{
    private $tournamentId = 1;
    private $url = 'http://live.fibaeurope.com/widgets/Schedule.ashx?roundID=8722&daysBack=500&daysForward=365&theme=0&lng=en';
    
    /**
     * @var Toto\TotalizerBundle\Service\Game
     */ 
    private $gameService;

    /**
     * @var Toto\TotalizerBundle\Service\Team
     */ 
    private $teamService;

    /**
     * @var Toto\TotalizerBundle\Service\Tournament
     */ 
    private $tournamentService;

    /**
     * @var Toto\TotalizerBundle\Entity\Tournament
     */ 
    private $tournament;

    public function __construct(Game $gameService, 
        Tournament $tournamentService,
        Team $teamService,
        $cacheDir
        )
    {
        $this->gameService = $gameService;
        $this->tournamentService = $tournamentService;
        $this->teamService = $teamService;
        $this->cacheDir = $cacheDir;

        $this->tournament = $this->tournamentService->getById($this->tournamentId);

        if (!$this->tournament) {
            throw new \RuntimeException('Tournament not found');
        }
    }

    public function import()
    {
        $data = $this->grab();
        if ($data) {
            $this->store($data);
        }

        return true;
    }

    private function grab()
    {
        $response = $this->curlGet($this->url);

        return $this->parse($response);
    }

    private function parse($response)
    {
        $return = array();
        $response = json_decode($response);

        foreach ($response->games as $game) {

            $date = new \DateTime();
            $date->setTimestamp(strtotime($game->strDate . ' ' . $game->strTime));
            $date->modify('+1 hour'); // timezone quick fix

            $homeTeamScore = $game->finalScoreTeamA;
            $homeTeam = $this->teamService->getByTournamentAndCode($this->tournament, $game->teamAName);

            $awayTeamScore = $game->finalScoreTeamB;
            $awayTeam = $this->teamService->getByTournamentAndCode($this->tournament, $game->teamBName);

            if (!$awayTeam || !$homeTeam) {
                continue;
            }

            $return[] = array(
                'time' => $date,
                'team' => array(
                    'home' => $homeTeam,
                    'away' => $awayTeam
                ),
            );
        }

        return $return;
    }

    private function store(array $data)
    {
        foreach ($data as $item) {
            $this->gameService->createNew($item['time'], $item['team']);
        }

        $this->gameService->save();
    }
}