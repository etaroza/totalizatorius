<?php
namespace Toto\ImportBundle\MiscImporter;

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

    private $data = array();

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
        $this->data = array();
        $response = json_decode($response);

        foreach ($response->games as $game) {

            $homeName = $game->teamAName;
            $awayName = $game->teamAName;

            if (!$this->exists($homeName)) {
                $homeTeam = $this->teamService->getByTournamentAndCode($this->tournament, $game->teamAName);
                $this->add($homeName, $homeTeam, $game->teamALogo);
            }

            if (!$this->exists($awayName)) {
                $awayTeam = $this->teamService->getByTournamentAndCode($this->tournament, $game->teamBName);
                $this->add($awayName, $awayTeam, $game->teamBLogo);
            }
        }

        return $this->data;
    }

    private function exists($key)
    {
        return isset($this->data[$key]);
    }

    private function add($key, $team, $logo)
    {
        $this->data[$key] = array(
            'team' => $team,
            'logo' => $logo,
        );
    }

    private function store(array $data)
    {
        foreach ($data as $item) {
            $this->teamService->updateLogo($item['team'], $item['logo']);
        }

        $this->teamService->save();
    }
}