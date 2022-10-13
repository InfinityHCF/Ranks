<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\credentialsengine\provider\mysql;


use pocketmine\Server;
use sergittos\credentialsengine\provider\mysql\task\CheckRankTask;
use sergittos\credentialsengine\provider\mysql\task\CreateTablesTask;
use sergittos\credentialsengine\provider\mysql\task\LoadSessionTask;
use sergittos\credentialsengine\provider\mysql\task\MysqlTask;
use sergittos\credentialsengine\provider\mysql\task\SaveSessionTask;
use sergittos\credentialsengine\provider\mysql\task\SetRankTask;
use sergittos\credentialsengine\provider\Provider;
use sergittos\credentialsengine\CredentialsEngine;
use sergittos\credentialsengine\session\BaseSession;
use sergittos\credentialsengine\session\OfflineSession;
use sergittos\credentialsengine\session\Session;
use sergittos\credentialsengine\utils\ConfigGetter;
use function basename;
use function strrpos;
use function substr;
use function var_dump;

class MysqlProvider extends Provider {

    private MysqlCredentials $credentials;

    public function __construct() {
        $this->credentials = MysqlCredentials::fromData(ConfigGetter::getMysqlCredentials());
        $this->submitTask(new CreateTablesTask($this));

        // $this->transferJsonPlayerData();
    }

    public function loadSession(BaseSession $session): void {
        $this->submitTask(new LoadSessionTask($this, $session));
    }

    public function saveSession(BaseSession $session): void {
        // $this->submitTask(new SaveSessionTask($this, $session));
    }

    public function setRank(BaseSession $session): void {
        $this->submitTask(new SetRankTask($this, $session, $session->getRankId()));
    }

    public function checkRank(Session $session): void {
        $this->submitTask(new CheckRankTask($this, $session));
    }

    private function submitTask(MysqlTask $task): void {
        Server::getInstance()->getAsyncPool()->submitTask($task);
    }

    private function transferJsonPlayerData(): void {
        $players_dir = CredentialsEngine::getInstance()->getDataFolder() . "players";
        if(!is_dir($players_dir)) {
            return;
        }
        foreach(glob($players_dir . "/*.json") as $file_name) {
            foreach(json_decode(file_get_contents($file_name), true) as $rank_id) {
                $session = new OfflineSession(basename(substr($file_name, 0, strrpos($file_name, "."))));
                $this->loadSession($session);
                $session->setRankId($rank_id);
                $this->saveSession($session);
            }
            unlink($file_name);
        }
        rmdir($players_dir);
    }

    public function getCredentials(): MysqlCredentials {
        return $this->credentials;
    }

}