<?php
namespace Toto\ImportBundle\ResultImporter;

use Doctrine\ORM\EntityManager;
use Toto\TotalizerBundle\Service\Game;
use Toto\TotalizerBundle\Service\Tournament;
use Toto\TotalizerBundle\Service\Team;


class Eurobasket2013Importer implements ImporterInterface
{
    private $invalidateCacheTime = 900; // 15 min
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

    /**
     * @var string
     */
    private $cacheDir;

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
        $file = $this->getCacheFile();

        // download data
        if (!file_exists($file)) {
            $fp = fopen ($file, 'w+');
            $ch = curl_init($this->url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_FILE, $fp); // write curl response to file
            curl_exec($ch); // get curl response
            curl_close($ch);
            fclose($fp);
        } 

        $response = file_get_contents($file);

        return $this->parse($response);
    }

    private function parse($response)
    {
        $return = array();
        $response = json_decode($response);

        foreach ($response->games as $game) {

            // grab only finished games
            if ($game->status != 2) {
                continue;
            }

            $date = new \DateTime();
            $date->setTimestamp(strtotime($game->strDate . ' ' . $game->strTime));
            $date->modify('+1 hour');

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
                'score' => array(
                    'home' => $homeTeamScore,
                    'away' => $awayTeamScore,
                ),
            );
        }

        return $return;
    }

    private function store(array $data)
    {
        foreach ($data as $item) {
            $this->gameService->updateScore($item['time'], $item['team'], $item['score']);
        }

        $this->gameService->updateScoreFlush();
    }

    private function getCacheFile()
    {
        $file = $this->cacheDir . '/cache_tournament_' . $this->tournamentId . '.tmp';
        $dir = dirname($file);
        
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        // cleanup oldies
        if (file_exists($file) && (time() - filemtime($file)) > $this->invalidateCacheTime) {
            unlink($file);
        }

        return $file;
    }
}