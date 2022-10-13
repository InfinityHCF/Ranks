<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\credentialsengine\provider\mysql\task;


use mysqli;
use sergittos\credentialsengine\CredentialsEngine;
use sergittos\credentialsengine\provider\mysql\MysqlProvider;
use sergittos\credentialsengine\session\Session;
use sergittos\credentialsengine\session\SessionFactory;

class CheckRankTask extends MysqlTask {

    private string $username;

    public function __construct(MysqlProvider $provider, Session $session) {
        $this->username = $session->getLowercaseName();
        parent::__construct($provider);
    }

    public function execute(mysqli $mysqli): void {
        $statement = $mysqli->prepare("SELECT rank_id FROM users WHERE username = ?");
        $statement->bind_param("s", ...[$this->username]);
        $statement->execute();

        $rank_id = null;
        if(($result = $statement->get_result()) !== false) {
            while($row = $result->fetch_assoc()) {
                $rank_id = $row["rank_id"];
            }
        }

        $statement->free_result();
        $statement->close();

        $this->setResult($rank_id);
    }

    public function onCompletion(): void {
        $session = SessionFactory::getSessionByName($this->username);
        $rank_id = $this->getResult();
        if($session !== null and $rank_id !== null) {
            $session->setRank(CredentialsEngine::getInstance()->getRankManager()->getRankById($rank_id));
        }
    }

}